<?php 
namespace App\Http\Controllers\Frontend\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CorredoresDetalle;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TvController;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Frontend\Access\LoginRequest;
use Auth;
use App\Http\Requests\Frontend\Access\RegisterRequest;
use App\Models\Logeo;
use App\Models\Permiso;
use App\Http\Requests\Frontend\Access\VerificationCodeRequest;
use App\Models\MultiFactorCodes;
use App\Repositories\Frontend\Auth\AuthenticationContract;
use Arcanedev\Support\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jenssegers\Agent\Agent;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend\Auth
 */
class AuthController extends Controller
{

    use ThrottlesLogins;

    /**
     * @param AuthenticationContract $auth
     */
    public function __construct(AuthenticationContract $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {
        return view('frontend.auth.register');
    }

    /**
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegister(RegisterRequest $request)
    {
        if (config('access.users.confirm_email')) {
            $this->auth->create($request->all());
            return redirect()->route('home')->withFlashSuccess("Your account was successfully created. We have sent you an e-mail to confirm your account.");
        } else {
            //Use native auth login because do not need to check status when registering
            auth()->login($this->auth->create($request->all()));
            return redirect()->route('frontend.dashboard');
        }
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('frontend.auth.login')
            ->withSocialiteLinks($this->getSocialLinks());
    }

    /**
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLogin(LoginRequest $request)
    {
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request))
            return $this->sendLockoutResponse($request);

        //Don't know why the exception handler is not catching this
        try {
            $this->auth->login($request);

            if ($throttles)
                $this->clearLoginAttempts($request);

            $logues = Logeo::where('id_user', Auth::user()->id)->whereRaw('fecha_logeo = CURRENT_DATE')->first();
            if ($logues) {
              $contador = $logues->contador + 1;
              $logues->update([
                'contador' => $contador,
                'fecha_logeo' => Carbon::now()->toDateString()
              ]);
            } else {
              Logeo::create([
                'id_user' => Auth::user()->id,
                'contador' => 1,
                'fecha_logeo' => Carbon::now()->toDateString()
              ]);
            }

            $permisos_model = new Permiso();
            $permissions = $permisos_model->getPermissionsByUserId(Auth::user()->id);
            Session::put('permissions', $permissions);

            if(Auth::user()->actualizo_passw==0):
                return redirect()->intended('/auth/password/change/');
            else:
                return redirect()->intended('/dashboard/');
            endif;
        } catch (GeneralException $e) {
            // If the login attempt was unsuccessful we will increment the number of attempts
            // to login and redirect the user back to the login form. Of course, when this
            // user surpasses their maximum number of attempts they will get locked out.
            if ($throttles)
                $this->incrementLoginAttempts($request);

            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param $provider
     * @return mixed
     */
    public function loginThirdParty(Request $request, $provider)
    {
        return $this->auth->loginThirdParty($request->all(), $provider);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getLogout()
    {   
        $user = User::where('id','=',auth()->user()->id)->first();
        if ($user->id_corredores_detalle !== null) {
            $corredores_detalle = CorredoresDetalle::where('id','=',$user->id_corredores_detalle)->first();
            $limpiarLlamadoTicket = new TvController;
            $user->id_corredores_detalle = null;
            $user->id_ventanilla = null;
            $corredores_detalle->updated_us = null;
            $corredores_detalle->updated_at = null;
            $user->save();
            $corredores_detalle->save();
            $limpiarLlamadoTicket->posponer_llamado_ticket_logout();
        }
        if($user->id_corredor_triaje !== null){
            $user->id_corredor_triaje=null;
            $user->save();
        }

        $updateLogeo = Logeo::where('id_user', auth()->user()->id)->orderBy('id', 'desc')->first();
        $updateLogeo->update([
          'logout_at' => Carbon::now(),
          'updated_at' => Carbon::now()
        ]);

        $this->auth->logout();
        return redirect()->route('home');
    }

    /**
     * @param $token
     * @return mixed
     * @throws \App\Exceptions\GeneralException
     */
    public function confirmAccount($token)
    {
        //Don't know why the exception handler is not catching this
        try {
            $this->auth->confirmAccount($token);
            return redirect()->route('frontend.dashboard')->withFlashSuccess("Your account has been successfully confirmed!");
        } catch (GeneralException $e) {
            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function resendConfirmationEmail($user_id)
    {
        //Don't know why the exception handler is not catching this
        try {
            $this->auth->resendConfirmationEmail($user_id);
            return redirect()->route('home')->withFlashSuccess("A new confirmation e-mail has been sent to the address on file.");
        } catch (GeneralException $e) {
            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }

    /**
     * Helper methods to get laravel's ThrottleLogin class to work with this package
     */

    /**
     * Get the path to the login route.
     *
     * @return string
     */
    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    /**
     * Determine if the class is using the ThrottlesLogins trait.
     *
     * @return bool
     */
    protected function isUsingThrottlesLoginsTrait()
    {
        return in_array(
            ThrottlesLogins::class, class_uses_recursive(get_class($this))
        );
    }

    /**
     * Generates social login links based on what is enabled
     * @return string
     */
    protected function getSocialLinks()
    {
        $socialite_enable = [];
        $socialite_links = '';

        if (getenv('GITHUB_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Github']), 'github');

        if (getenv('FACEBOOK_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Facebook']), 'facebook');

        if (getenv('TWITTER_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Twitter']), 'twitter');

        if (getenv('GOOGLE_CLIENT_ID') != '')
            $socialite_enable[] = link_to_route('auth.provider', trans('labels.login_with', ['social_media' => 'Google']), 'google');

        for ($i = 0; $i < count($socialite_enable); $i++) {
            $socialite_links .= ($socialite_links != '' ? '&nbsp;|&nbsp;' : '') . $socialite_enable[$i];
        }

        return $socialite_links;
    }

    /*public function dashboard() {
        
        $px = Auth::user()->passwords;
        $dni = Auth::user()->dni;
        if(bcrypt($dni)==$px){
            dd($dni);
        }
        else{
            dd($px);
        }
        
        $date = Carbon::now();
        $fecha = $date->format('d-m-Y');
        $model_stock = new Stock_farmacia;
        $model_carga = new \App\Models\Carga_inicial_stock;
        $model_farma = new Farmacia;
        $farma = $model_farma->GetFarmaByIdfarma($id_farmacia);
        $model_establecimiento = new Establecimiento;
        $establecimiento = $model_establecimiento->GetByIdfarmacia($id_farmacia);
        $nombre_farmacia = $farma->nombre;
        $id_establecimiento = $farma->id_establecimiento;
        $id_farmacia = $farma->id;
        //====================
        $model_producto = new Producto;
        $model_kardex = new Kardexe();

        $mes = 6;
        $fecha_inicio = $model_kardex->getFechaServidorRestaMeses(6);
        $fecha_fin = $model_kardex->getFechaServidor();

        $kardexs = $model_kardex->ShowTotalKardexByFarmacia($nombre_farmacia, $fecha_fin);
        
        $resultado = array();
        foreach ($kardexs as $k) {
        
            $arrayarticulo = array('codigo' => $k->codigo_producto,
                'nombre' => $k->nombre_producto,
                'unidad' => $k->unidad,
                'cantidad' => $k->total,
                'egresos' => $k->egresos,
                'nro_mes' => $k->nro_mes,
                'recetas_devueltas' => $k->recetas_devueltas,
            );

            $resultado[] = $arrayarticulo;
        }

        //----------------------------------------------------------------------
        $fecha_inicio_venc = $fecha_fin;
        $fecha_fin_venc = $model_kardex->getFechaServidorRestaMeses("-6");
        $stocks_proximos_vencer = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechasVencimiento("", $id_farmacia, $fecha_inicio_venc, $fecha_fin_venc);
        $count_proximos_vencer = count($stocks_proximos_vencer);
        $stocks_productos_vencidos = $model_kardex->GetKardexLotesByCodigoProductoAndIdFarmaciaAndFechaVencido("", $id_farmacia, $fecha_fin);
        $count_productos_vencidos = count($stocks_productos_vencidos);
        //----------------------------------------------------------------------
        
        return view('frontend.user.dashboard', compact('resultado', 'farma', 'fecha', 'constock', 'sinstock', 'minstock', 'id_farmacia', 'estado_carga', 'countMedicamento', 'countbiomedico', 'countinsumolaboratorio', 'countMaterialLaboratorio', 'countMaterialDental', 'countMatFotograficoAndFonotecnico', 'count_proximos_vencer', 'count_productos_vencidos', 'situacion', 'farmaciaActual', 'establecimiento','countMaterialQuirurgico','countProductosAfines'));
    }*/

    private function verificarReactivacionAutomatica($idMedico)
    {
      $historial = \Illuminate\Support\Facades\DB::table('historial_medicos_desactivados')
            ->where('id_medico', $idMedico)
            ->orderBy('created_at', 'desc')
            ->first();
        
        
      if (!$historial || is_null($historial->periodo) || is_null($historial->total_periodo)) {
        return false;
      }
        
      if ($historial->periodo == 0) {
        return false;
      }
        
      $fechaDeshabilitacion = Carbon::parse($historial->created_at);
      $fechaActual = Carbon::now();
        
      $debeReactivar = false;
        
      // ===== VALIDACIÓN TEMPORAL PARA PRUEBAS: 5 MINUTOS =====
      /*$minutosPasados = $fechaActual->diffInMinutes($fechaDeshabilitacion);
        
      if ($minutosPasados >= 2) {
        $debeReactivar = true;
      }*/

      if (!$debeReactivar) {
        $fechaSoloFecha = Carbon::parse($historial->fecha_deshabilitacion);
              
        if ($historial->periodo == 1) {
          $diasPasados = $fechaActual->diffInDays($fechaSoloFecha);
          if ($diasPasados >= $historial->total_periodo) {
            $debeReactivar = true;
          }
        } elseif ($historial->periodo == 2) {
          $mesesPasados = $fechaActual->diffInMonths($fechaSoloFecha);
          if ($mesesPasados >= $historial->total_periodo) {
            $debeReactivar = true;
          }
        }
      }
        
      if ($debeReactivar) {
        \Illuminate\Support\Facades\DB::table('medicos')
            ->where('id', $idMedico)
            ->update(array(
              'estado' => 1,
              'updated_at' => Carbon::now()
        ));
            
        \Illuminate\Support\Facades\DB::table('historial_medicos_desactivados')->insert(array(
            'id_medico' => $idMedico,
            'motivo' => 'Reactivación automática por cumplimiento de período',
            'fecha_deshabilitacion' => Carbon::now(),
            'estado' => 1,
            'created_at' => Carbon::now(),
            'created_us' => null,
            'updated_at' => Carbon::now(),
            'updated_us' => null
        ));

        return true;
      }
      return false;
    }

    public function verificationCode(Request $request) {
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);

        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);

        $device = $agent->isMobile() ? 'Mobile' : 'Desktop';

        $encryptedToken = $request->query('payload'); 
        $decryptedData = Crypt::decrypt($encryptedToken);
        $tokenData = unserialize($decryptedData);

        $data = [
            'email' => $tokenData->email,
            'password' => $tokenData->password,
        ];

        $requestLogin = new Request($data);

        $user = User::where('email', $requestLogin->email)->first();
        if (!$user) {
            return redirect()->back()->withInput()->withFlashDanger('Las credenciales no coinciden');
        }

        // estado validacion..
        $user_medico = \Illuminate\Support\Facades\DB::table('users as usr')
                  ->join('medicos as me', 'usr.dni', '=', 'me.dni')
                  ->select('me.*', 'estado')
                  ->where('me.dni', $user->dni)
                  ->first();

        if ($user_medico->estado == 0) {
          $this->verificarReactivacionAutomatica($user_medico->id);
        }
        // fin estado validacion

        // $code = MultiFactorCodes::where('user_id', $user->id)->where('is_used', false)->where('code', $request->code)->latest('created_at')->first();
        $code = MultiFactorCodes::where('user_id', $user->id)
                  ->where('action_type', 'LOGIN')
                  ->where('is_used', false)
                  ->latest('created_at')
                  ->first();
        if (!$code || !$code->expires_at || $code->code != $request->code) {
            return redirect()->back()->withInput()->withFlashDanger('C?digo de verificaci?n invalido');
        }
        $expires_at = Carbon::parse($code->expires_at);
        if (!isset($code->expires_at) || Carbon::now()->gt($expires_at)) {
            return redirect()->back()->withInput()->withFlashDanger('El c?digo ha expirado.');
        }

        $code->update([
            'is_used' => true,
            'verified_at' => Carbon::now()
        ]);

        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $this->hasTooManyLoginAttempts($requestLogin))
            return $this->sendLockoutResponse($requestLogin);
        try {
            $this->auth->login($requestLogin);
            if ($throttles)
                $this->clearLoginAttempts($requestLogin);

            $newLogeo = Logeo::create([
              'id_user' => Auth::user()->id,
              'ip_address' => $ipAddress,
              'agent' => $userAgent,
              'device_name' => $device,
              'platform' => $platform . ' ' . $platformVersion,
              'browser' => $browser . ' ' . $browserVersion,
              'login_at' => Carbon::now(),
              'created_at' => Carbon::now(),
            ]);

            $permisos_model = new Permiso();
            $permissions = $permisos_model->getPermissionsByUserId(Auth::user()->id);
            Session::put('permissions', $permissions);

            if(Auth::user()->actualizo_passw==0):
                return redirect()->intended('/auth/password/change/');
            else:
                return redirect()->intended('/dashboard/');
            endif;
            
        } catch (GeneralException $e) {
            if ($throttles)
                $this->incrementLoginAttempts($requestLogin);

            return redirect()->back()->withInput()->withFlashDanger($e->getMessage());
        }
    }
}