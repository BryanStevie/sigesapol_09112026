@extends('frontend.layouts.master3')
@section('renderjs')
<?php use Carbon\Carbon; ?>
<!--<script src="http://192.168.0.33:8092/js/vendor/bootbox.min.js"></script>-->
<!--
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
-->
{!! HTML::script("plugins/jQuery/jquery-ui.js") !!}
{!! HTML::style("plugins/jQuery/jquery-ui.css") !!}

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript" src="https://dsp.reniec.gob.pe/refirma_invoker/resources/js/client.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="{{asset('js/jquery.inputmask.bundle.min.js')}}"></script>

<style>
    .loader {
      width: 100%;
      height: 100%;
      /*height: 1500px;*/
      overflow: hidden;
      top: 0px;
      left: 0px;
      z-index: 10000;
      text-align: center;
      position: absolute;
      background-color: #000;
      opacity: 0.6;
      filter: alpha(opacity=40);
      display: none;
    }
    .dataTables_processing {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 500px !important;
      font-size: 1.7em;
      border: 0px;
      margin-left: -17% !important;
      text-align: center;
      background: #3c8dbc;
      color: #FFFFFF;
    }
    /*.modal-dialog {
      width: 90%;
    }*/
    .title-dialog {
      font-size: 18px;
      font-family: Arial, Helvetica, sans-serif;
    }
    .table-row {
      cursor: pointer;
      transition: background-color 0.3s  !important;
    }
  
    .table-row:hover {
      background-color: lightgreen !important;
    }
    .ui-autocomplete {
      position: absolute;
      top: 100%;
      left: 0;
      z-index: 1000;
      display: none;
      float: left;
      min-width: 160px;
      padding: 5px 7px;
      margin: 2px 0 0;
      list-style: none;
      font-size: 15px;
      font-family: Arial, Helvetica, sans-serif;
      text-align: left;
      background-color: #ffffff;
      border: 1px solid #cccccc;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
      background-clip: padding-box;
    }
    .glyphicon {
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: normal;
        line-height: 1;
        position: relative;
        top: 1px;
        display: inline-block;
        font-size: inherit;
        text-rendering: auto;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
    .ui-autocomplete>li>div {
      display: block;
      padding: 3px 20px;
      clear: both;
      font-weight: normal;
      line-height: 1.42857143;
      color: #333333;
      white-space: nowrap;
    }
    .ui-state-hover,
    .ui-state-active,
    .ui-state-focus {
      text-decoration: none;
      color: #FFFFFF !important;
      background: #1d71b8 !important;
      cursor: pointer;
    }
    .ui-helper-hidden-accessible {
      border: 0;
      clip: rect(0 0 0 0);
      height: 1px;
      margin: -1px;
      overflow: hidden;
      padding: 0;
      position: absolute;
      width: 1px;
    }
    input[readonly] {
      background-color: #eeeeee;
      text-align: center
    }
    span:focus,
    textarea:focus,
    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="datetime"]:focus,
    input[type="datetime-local"]:focus,
    input[type="date"]:focus,
    input[type="month"]:focus,
    input[type="time"]:focus,
    input[type="week"]:focus,
    input[type="number"]:focus,
    input[type="email"]:focus,
    input[type="url"]:focus,
    input[type="search"]:focus,
    input[type="tel"]:focus,
    input[type="color"]:focus,
    .uneditable-input:focus {
      border-color: #2196F3;
      box-shadow: 0 1px 1px #2196F3 inset, 0 0 8px #2196F3;
      outline: 0 none;
    }
    /*.fc-event-container{margin:20px!important}*/
    .fc-day-grid-event {
      /*width:150px;*/
      margin: 10px;
      text-align: center;
      margin-top: 40px;
      /*background:#5cb85c*/
      background: transparent;
      color: #009966
    }
    .fc-day-grid-event :hover {
      color: #009966
    }
    .fc-content {
      padding: 5px !important;
    }
    .fc-title {
      text-align: center;
      font-size: 12px
    }
  
    .swal2-icon.swal2-info {
      font-size: 20px !important;
    }
</style>
  

<style>
  .modal-dialog {
    width: 1200px !important;
  }

  .suggest-element {
    margin-left: 5px;
    margin-top: 5px;
    width: 350px;
    cursor: pointer;
  }

  #suggestions {
    width: 350px;
    height: 150px;
    overflow: auto;
  }


  .alert {
    padding: 10px;
    background-position: 2% 7px;
    background-repeat: no-repeat;
    background-size: auto 35px;
    /*background-color: rgba(0, 0, 0, 0);*/
    border: 0;
    min-width: auto !important;
    text-align: left;
    padding-left: 68px;
    font-size: 14px;
    margin-bottom: 10px;
  }

  .alert.alert-danger {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAARVBMVEX////7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0P7y0NAQECdhUHYsUKGdEG1l0JLSEBuYkHkukPKU8NPAAAADnRSTlMA4EDAoCAQ8IBgkDDQUFItelQAAAC0SURBVHherZBLDsMwCAVD7Nj5gvPr/Y/a0FpGLTibdnbozQJN829agPZu7/CiuxE8C76+T/hiqgruLbjqh5ip/TljZrb3AQuDKYAIYO09L3TBRq/3EHk4shCDEkZkTqITmdGMLIIO7r8FbzfaiDappSNjIkpZcCqyCDo4WAJYkR8prTp4ADSBUCIX9uPY5epL5AJdyBWDRDYFHCWyCiXBObKwrh+nl4g2TiLVhSXW97g0v/MEHIQbCYeFmYAAAAAASUVORK5CYII=);
    border-top: 1px solid rgba(140, 0, 0, 0.4);
    border-bottom: 1px solid rgba(140, 0, 0, 0.4);
  }

  .alert.alert-info {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAkCAYAAADhAJiYAAADBklEQVR42u3YyWsTURwHcP8aQUWtWlu7JEbtpngQvIqXevDWm1fXehO12BTErdQiVMFQ8ODBusWkmSa1lVrTxdrG0FA9WJtkljfL19/LTDKMibl0EorkwReSCQ8+fN+bl2G2AdhSqYH+P1CpAS0DfXUI+ufzUCfaoQptYMIxsMhRsE/d0L4/BNR0qcnug4y159CmTkOLdkKNdnBQAaNEjkAZ90EOH4YcOQ51dQQVBRnLN6DHuqDFOqGZGEpbaVDICynkAVvoRUVAxuqQE8PjXC7C/AX64KG0gq3ch6sgQ1zkEBtTph2FY8JeApkYKdgCMdgMPTMP10D64kUOsTDtDsw/27FAIge9b4Y8e8EdELS0jTAh5TF2Ow5Q9l0TDDWNTYOMjSkLQREcEAdGCRfvHdFcLgI1EegQtPXY5kFactAE2JDyGHvvONrhILZ81w3QIxNQCEfw2JvYxpRYKrMdSqNLoNSzPMBGTJwEmz4HuSSGMnkWYrgjv1Rm3jZATQ5vHqT//sgRVhtmI8p0NwADbOF6EUaOX4GiZCHGzhAkD2ogUD20XxNw5baXhRN2G2EvR+QwhqFDme91YJgiQfpyCTamkTAHaQm97p1DauIBR1jxWI14CHMNuqZCmbsKmcKYBLkYQzkA9m3APRA/P6TxrvztbCfYkoOoqgKVydTQ5ZIYMdgKQ91wD8SHvj7JAYXb2T5jmqEkhiEnHnNIESb7po72joCK/Ntray8sRD7UBkVeGoBEIQilwYFRUwFU9HmIrdzjCHtpKNJXP48FqSfIfspe2jf+Sj4P2UOMnOKQfCOE6Ye0eMdqZR+yr/fwc8icXA2QmnzCD7pCI+JiH8SFPt4KYXYjO7YTLDFYPZCensu3QakjzC2I8zcJsguZsR3IvNoOfWO2eiA+tJ9jfI9Q+qEkn1JGoCz1UW5D+/HSnFhNUCqVQjwezyUajSIUChW+02/VB42OjqJMaqCtB5qZmUEgEIDf73eEXxMEofogPtLpNHp6euDz+Xhyn+lauUm1tx81UMXzBw5wT9JYWi53AAAAAElFTkSuQmCC);
    border-top: 1px solid rgba(255, 165, 0, 0.4);
    border-bottom: 1px solid rgba(255, 165, 0, 0.4);
    color: rgb(225, 75, 0);
  }

  .alert.alert-success {
    background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAAqFBMVEX////w8PDw8PDw8PDw8PDw8PDw8PDw8PDW1tbn5+ff398PnVjd3d3i4uIPnVjk5OQPnVjg4OAPnVgzqW8PnVgPnVjT09Pw8PDG4NOp1sBjvJHu7u4PmVYqp2ri6+YcomFHsn4bmlzHzsoPnFeuxbl9tJk0n2t/xqSVvqqNzK1ZqYKJuaJVt4c4rHQnnWPl5eXj4+MPm1fe3t6b0bfc3NxBo3TY2NjX19eKj23kAAAAFXRSTlMA8BCwcFCgkP4f3NDnrzCHoM8Qc/Avz3otAAAA9klEQVR4Xq2T2XKDMAwAMRACNGdbyUDus/d9/f+f1VHEiFamfcm+7qIZGTs4IWEcGeswURz6dGIbJCrp0MeC6QTBCADO2Hetokv+vNXbyvnLAc9v8cA+NH4/veAFEr/f7HIe0OJvETMKYhLlLw8fiNinICKxnHHCfr92QY8CcwwAloX41cFjSoElCnBU89pvkeCAeAPH4or89Bp1YG+g5uUOPQEP5wWbgZzjPRDiU1mTmD/wggyvGVspZgCP4rGvjrpYuAWFTP+sounzwPO7n8SnNEBdmGesGZLUV+6V/ZilLt7F+6/95BMxHf71cCZfefbf0zsl3w3QOjVy6QFpAAAAAElFTkSuQmCC);
    border-top: 1px solid limegreen;
    border-bottom: 1px solid limegreen;
  }

  .form-control {
    height: 28px;
    text-align: left !important;
  }

  .form-group {
    margin-bottom: 10px;
  }

  #divHorariox {
    /*height: 200px!important;;*/
    /*background-color: #fed9ff; */
    /*width: 600px; */
    width: 100%;
    height: 550px;
    overflow-x: hidden;
    overflow-y: auto;
    text-align: center;
    padding: 0px !important;
  }


  /********************/

  body {
    font-family: 'Montserrat', 'Lato', 'Open Sans', 'Helvetica Neue', Helvetica, Calibri, Arial, sans-serif;
    color: #6b7381;
    background: #f2f2f2;
  }

  .jumbotron {
    background: #6b7381;
    color: #bdc1c8;
  }

  .jumbotron h1 {
    color: #fff;
  }

  .example {
    margin: 4rem auto;
  }

  .example>.row {
    margin-top: 2rem;
    height: 5rem;
    vertical-align: middle;
    text-align: center;
    border: 1px solid rgba(189, 193, 200, 0.5);
  }

  .example>.row:first-of-type {
    border: none;
    height: auto;
    text-align: left;
  }

  .example h3 {
    font-weight: 400;
  }

  .example h3>small {
    font-weight: 200;
    font-size: 0.75em;
    color: #939aa5;
  }

  .example h6 {
    font-weight: 700;
    font-size: 0.65rem;
    letter-spacing: 3.32px;
    text-transform: uppercase;
    color: #bdc1c8;
    margin: 0;
    line-height: 5rem;
  }

  .example .btn-toggle {
    top: 50%;
    transform: translateY(-50%);
  }

  .btn-toggle {
    margin: 0 4rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
    color: #6b7381;
    background: #bdc1c8;
  }

  .btn-toggle:focus,
  .btn-toggle.focus,
  .btn-toggle:focus.active,
  .btn-toggle.focus.active {
    outline: none;
  }

  .btn-toggle:before,
  .btn-toggle:after {
    line-height: 1.5rem;
    width: 4rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle:before {
    content: 'NO';
    left: -4rem;
  }

  .btn-toggle:after {
    content: 'SI';
    right: -4rem;
    opacity: 0.5;
  }

  .btn-toggle>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }

  .btn-toggle.active:before {
    opacity: 0.5;
  }

  .btn-toggle.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }

  .btn-toggle.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    display: none;
  }

  .btn-toggle:before,
  .btn-toggle:after {
    color: #6b7381;
  }

  .btn-toggle.active {
    background-color: #29b5a8;
  }

  .btn-toggle.btn-lg {
    margin: 0 5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 2.5rem;
    width: 5rem;
    border-radius: 2.5rem;
  }

  .btn-toggle.btn-lg:focus,
  .btn-toggle.btn-lg.focus,
  .btn-toggle.btn-lg:focus.active,
  .btn-toggle.btn-lg.focus.active {
    outline: none;
  }

  .btn-toggle.btn-lg:before,
  .btn-toggle.btn-lg:after {
    line-height: 2.5rem;
    width: 5rem;
    text-align: center;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle.btn-lg:before {
    content: 'NO';
    left: -5rem;
  }

  .btn-toggle.btn-lg:after {
    content: 'SI';
    right: -5rem;
    opacity: 0.5;
  }

  .btn-toggle.btn-lg>.handle {
    position: absolute;
    top: 0.3125rem;
    left: 0.3125rem;
    width: 1.875rem;
    height: 1.875rem;
    border-radius: 1.875rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.btn-lg.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.btn-lg.active>.handle {
    left: 2.8125rem;
    transition: left 0.25s;
  }

  .btn-toggle.btn-lg.active:before {
    opacity: 0.5;
  }

  .btn-toggle.btn-lg.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-lg.btn-sm:before,
  .btn-toggle.btn-lg.btn-sm:after {
    line-height: 0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.6875rem;
    width: 3.875rem;
  }

  .btn-toggle.btn-lg.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-lg.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-lg.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-lg.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-lg.btn-xs:before,
  .btn-toggle.btn-lg.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-sm {
    margin: 0 0.5rem;
    padding: 0;
    position: relative;
    border: none;
    height: 1.5rem;
    width: 3rem;
    border-radius: 1.5rem;
  }

  .btn-toggle.btn-sm:focus,
  .btn-toggle.btn-sm.focus,
  .btn-toggle.btn-sm:focus.active,
  .btn-toggle.btn-sm.focus.active {
    outline: none;
  }

  .btn-toggle.btn-sm:before,
  .btn-toggle.btn-sm:after {
    line-height: 1.5rem;
    width: 0.5rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.55rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle.btn-sm:before {
    content: 'NO';
    left: -0.5rem;
  }

  .btn-toggle.btn-sm:after {
    content: 'SI';
    right: -0.5rem;
    opacity: 0.5;
  }

  .btn-toggle.btn-sm>.handle {
    position: absolute;
    top: 0.1875rem;
    left: 0.1875rem;
    width: 1.125rem;
    height: 1.125rem;
    border-radius: 1.125rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.btn-sm.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.btn-sm.active>.handle {
    left: 1.6875rem;
    transition: left 0.25s;
  }

  .btn-toggle.btn-sm.active:before {
    opacity: 0.5;
  }

  .btn-toggle.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-sm.btn-sm:before,
  .btn-toggle.btn-sm.btn-sm:after {
    line-height: -0.5rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.4125rem;
    width: 2.325rem;
  }

  .btn-toggle.btn-sm.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-sm.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-sm.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-sm.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-sm.btn-xs:before,
  .btn-toggle.btn-sm.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-xs {
    margin: 0 0;
    padding: 0;
    position: relative;
    border: none;
    height: 1rem;
    width: 2rem;
    border-radius: 1rem;
  }

  .btn-toggle.btn-xs:focus,
  .btn-toggle.btn-xs.focus,
  .btn-toggle.btn-xs:focus.active,
  .btn-toggle.btn-xs.focus.active {
    outline: none;
  }

  .btn-toggle.btn-xs:before,
  .btn-toggle.btn-xs:after {
    line-height: 1rem;
    width: 0;
    text-align: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    position: absolute;
    bottom: 0;
    transition: opacity 0.25s;
  }

  .btn-toggle.btn-xs:before {
    content: 'NO';
    left: 0;
  }

  .btn-toggle.btn-xs:after {
    content: 'SI';
    right: 0;
    opacity: 0.5;
  }

  .btn-toggle.btn-xs>.handle {
    position: absolute;
    top: 0.125rem;
    left: 0.125rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 0.75rem;
    background: #fff;
    transition: left 0.25s;
  }

  .btn-toggle.btn-xs.active {
    transition: background-color 0.25s;
  }

  .btn-toggle.btn-xs.active>.handle {
    left: 1.125rem;
    transition: left 0.25s;
  }

  .btn-toggle.btn-xs.active:before {
    opacity: 0.5;
  }

  .btn-toggle.btn-xs.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-xs.btn-sm:before,
  .btn-toggle.btn-xs.btn-sm:after {
    line-height: -1rem;
    color: #fff;
    letter-spacing: 0.75px;
    left: 0.275rem;
    width: 1.55rem;
  }

  .btn-toggle.btn-xs.btn-sm:before {
    text-align: right;
  }

  .btn-toggle.btn-xs.btn-sm:after {
    text-align: left;
    opacity: 0;
  }

  .btn-toggle.btn-xs.btn-sm.active:before {
    opacity: 0;
  }

  .btn-toggle.btn-xs.btn-sm.active:after {
    opacity: 1;
  }

  .btn-toggle.btn-xs.btn-xs:before,
  .btn-toggle.btn-xs.btn-xs:after {
    display: none;
  }

  .btn-toggle.btn-secondary {
    color: #6b7381;
    background: #bdc1c8;
  }

  .btn-toggle.btn-secondary:before,
  .btn-toggle.btn-secondary:after {
    color: #6b7381;
  }

  .btn-toggle.btn-secondary.active {
    background-color: #ff8300;
  }
  
  .icon-doc-firmado {
    color: #5cb85c;
    font-size: 3rem;
  }

  .texto-tamaño{
    font-family: Roboto, "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 12px;
  }

  .size-titulo {
    font-size: 1.2rem;
    font-weight: 700;
    text-align: center;
  }

  .table-borderless tbody > tr > td,.table-borderless tbody > tr > th{
    padding: 2px 8px !important;
    border: 0px solid;
  }

  .table-borderless{
    margin-bottom: 0px !important;
  }
</style>

<script>
  $(document).ready(function () {
    const consejeria = {!! json_encode($consejeria) !!};
    if (consejeria && consejeria.ruta_firma) {
      const url = `/${moment().format("YYYY/MM/DD")}/${consejeria.ruta_firma}`
      $("#btnShowPdfFirmado").attr("href", url);
    }
  });

  function firmarPDF() {
    Swal.fire({
      title: 'Firmando PDF...',
      text: 'Por favor, espera un momento.',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
          Swal.showLoading();
      }
    });
    const id_consejeria = $("#id_consejeria").val();
    $.ajax({
        url: `/consejeria/firmar-pdf/${id_consejeria}`,
        type: "post",
        dataType: 'json'
    })
    .done(function(resultado) {
      Swal.close();
      if (resultado.sw) {
          var csv = "<?php echo url()?>/tmp/csv/" + resultado.csv + ".csv";
          $("#doc_firmar").val(`${resultado.csv}.7z`);
          $("#opt_firma").val(`${resultado.opt}`);
          initInvoker('W');
      }
    })
    .fail(function(jqXHR, textStatus) {
      console.log("Request failed: " + textStatus);
      Swal.close();
      Swal.fire({
        title: 'Error',
        text: 'Hubo un problema al firmar el PDF.',
        icon: 'error'
      });
    })
  }

  window.addEventListener('getArguments', async function(e) {
    await getArguments();
  });

  window.addEventListener('invokerOk', function(e) {
    MiFuncionOkWeb();
  });

  window.addEventListener('invokerCancel', function(e) {
    cancelFirma();
  });

  async function getArguments() {
    var CSRF_TOKEN = $('meta[name="_token"]').attr('content');
    $.post("{{route('postArgumentsConsejeriaHospitalizacion')}}", {
      _token: CSRF_TOKEN,
      type: "W",
      documentName: $('#doc_firmar').val(),
      entidad: $("#user_firma").val()
    }, function(data, status) {
      $("#argumentos_firma").val(data);
      sendArguments();
    });
  }

  async function sendArguments() {
    const arg = $("#argumentos_firma").val();
    await dispatchEventClient('sendArguments', arg);
  }

  function cancelFirma() {
    Swal.fire({
      icon: "warning",
      title: "CONSEJERIA HOSPITALIZACIÓN",
      html: '<h5>PROCESO DE FIRMA CANCELADO</h5>',
      showLoaderOnConfirm: true,
      onOpen: () => {
          Swal.showLoading();
          Swal.close();
        }
    });
  }

  async function MiFuncionOkWeb() {
    const id_consejeria = $("#id_consejeria").val();
    $.ajax({
      type: "PUT",
      url: `/consejeria/actualizar-firma/${id_consejeria}`,
      data: JSON.stringify({}),
      dataType: "json",
      success: function (response) {
        console.log(response);
        location.reload();
      },
      error: function (xhr, status, error) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Ocurrió un problema al actualizar la firma. Intente nuevamente.",
        });
      }
    });
  }
</script>

@endsection
@section('content')

<?php $grados = array("NINGUNO","ALUMNO","CADETE","ALFÉREZ DE SERVICIO","ALFÉREZ MAESTRO ARMERO","CAPITÁN","CAPITÁN DE SERVICIO","CAPITÁN MAESTRO ARMERO","COMANDANTE","COMANDANTE DE SERVICIO","COMANDANTE MAESTRO ARMERO","CORONEL","CORONEL DE SERVICIO","ESPECIALISTA BRIGADIER","ESPECIALISTA DE PRIMERA","ESPECIALISTA DE SEGUNDA","ESPECIALISTA DE TERCERA","ESPECIALISTA SUPERIOR","ESPECIALISTA TÉCNICO DE 1RA","ESPECIALISTA TÉCNICO DE 2DA","ESPECIALISTA TÉCNICO DE 3RA","GENERAL","GENERAL DE SERVICIO","MAYOR","MAYOR DE SERVICIO","MAYOR MAESTRO ARMERO","SUBOFICIAL BRIGADIER","SUBOFICIAL BRIGADIER DE SERVICIO","SUBOFICIAL DE PRIMERA","SUBOFICIAL DE PRIMERA DE SERVICIO","SUBOFICIAL DE SEGUNDA","SUBOFICIAL DE SEGUNDA DE SERVICIO","SUBOFICIAL DE TERCERA","SUBOFICIAL DE TERCERA DE SERVICIO","SUBOFICIAL SUPERIOR","SUBOFICIAL SUPERIOR DE SERVICIO","SUBOFICIAL TECNICO DE 1RA","SUBOFICIAL TECNICO DE 1RA DE SERVICIO","SUBOFICIAL TECNICO DE 2DA","SUBOFICIAL TECNICO DE 2DA DE SERVICIO","SUBOFICIAL TECNICO DE 3RA","SUBOFICIAL TECNICO DE 3RA DE SERVICIO","TENIENTE","TENIENTE DE SERVICIO","TENIENTE GENERAL","TENIENTE MAESTRO ARMERO");
?>

<div class="container" style="width:90%">
  <div class="row">
    <div class="col-sm-12 col-md-12">
      <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <h3 class="panel-title">MÓDULO REGISTRO DE PROCEDIMIENTOS</h3>
            </div>

            <div class="panel-body">

                <!--<div class="panel panel-info panel-bordered panel-sm panel-body">-->

                  <form class="form-horizontal" method="post" id="frmBloque" action="">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" value="{{ Auth::user()->id }}" name="id_user" id="id_user">
                      <input type="hidden" value="{{Auth::user()->id_farmacia}}" name="id_farmacia" id="id_farmacia">
                      <input type="hidden" name="id_hospitalizacion" id="id_hospitalizacion" value="{{$hospitalizacion->id}}">
                      <input type="hidden" name="id_consejeria" id="id_consejeria" value="{{$id_consejeria}}">
                      
                      <input type="hidden" id="argumentos_firma" value="" />
                      <input type="hidden" name="doc_firmar" id="doc_firmar">
                      <input type="hidden" name="opt_firma" id="opt_firma">
                      <input type="hidden" id="user_firma" value="{{ Auth::user()->dni }}" />

                      <div class="row">
                        <div class="col-md-6">
                          <div class="panel panel-info">
                              <div class="panel-heading">
                                <h3 class="panel-title size-titulo">DATOS DEL PACIENTE</h3>
                              </div>
                              <div class="panel-body">
                                <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;width: 22%;"><strong>N° DOCUMENTO</strong></th>
                                      <th style="text-align: left; border: 0px solid;width: 12%;"><strong>:</strong></th>
                                      <td style="border: 0px solid;width: 40%;">
                                          <input id="nro_dni_paciente" type="hidden" value="<?php echo $cita->nro_doc_ident?>" />
                                          <span id="nro_doc_ident" style="font-weight: bold;margin-left: -53px;"><?php echo $cita->tipo_doc_ident?> - <?php echo $cita->nro_doc_ident?> </span>
                                      </td>
                                      <th style="text-align: left; border: 0px solid;width: 10%;"><strong>N° DE HC</strong></th>
                                      <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                      <td style="border: 0px solid; text-transform: uppercase;">
                                          <span id="nro_historia" style="font-weight:bold;"><?php echo $cita->nro_historia?></span>
                                      </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>APELLIDOS Y NOMBRES</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0px solid; text-align: left;" colspan="4">
                                        <span id="nombre_paciente" style="margin-left: -53px;">
                                            <?php echo trim("{$cita->asegurado_paterno} {$cita->asegurado_materno} {$cita->asegurado_nombre}"); ?>
                                        </span>
                                    </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>FECHA DE NACIMIENTO</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0px solid; text-align: left; padding-left: 0;" colspan="4">
                                        <span id="edad" style="margin-left: -53px">
                                            <?php echo isset($cita->fecha_nac) ? $cita->fecha_nac : ''; ?>
                                            /
                                                EDAD (
                                                <?php
                                                    $fecha_nac = DateTime::createFromFormat('d/m/Y', $cita->fecha_nac);
                                                    $hoy = new DateTime();
                                                    if ($fecha_nac !== false) {
                                                        $edad = $fecha_nac->diff($hoy);
                                                        echo $edad->y . ' a ' . $edad->m . ' m y ' . $edad->d . ' d';
                                                    } else {
                                                        echo "Formato de fecha inválido";
                                                    }
                                                ?>
                                                )
                                            </strong>
                                        </span>
                                    </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>SEXO</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0; position: relative; padding-left: 0;">
                                      <span id="sexo" style="position: relative; text-transform: uppercase; left: -52px;">
                                        <?php echo isset($cita->sexo) ? ($cita->sexo == "M" ? "MASCULINO" : "FEMENINO") : ''; ?>
                                      </span>
                                    </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>TIPO DE BENEFICIARIO</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0px solid; text-transform: uppercase;">
                                        <span id="tipo_beneficiario" style="position: relative; text-transform: uppercase; left: -52px;"><?php echo $cita->parentesco; ?></span>
                                    </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>DATOS DEL TITULAR</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0px solid; text-transform: uppercase;" colspan="4">
                                        <span id="dato_titular_name" style="font-weight: bold; margin-left: -53px;"><?php echo trim("{$cita->asegurado_paterno} {$cita->asegurado_materno} {$cita->asegurado_nombre}");?></span>
                                    </td>
                                  </tr>
                                  <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0px solid;"><strong>CIP/DNI/GRADO</strong></th>
                                    <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                    <td style="border: 0px solid;" colspan="4">
                                        <span id="nrodoctitular" style="margin-left: -53px;"><?php echo $cita->cip_titular;?> / <?php echo $cita->nro_doc_ident;?> / <?php echo $cita->grado_titular;?></span>
                                    </td>
                                  </tr>
                                </table>
                              </div>
                           </div>
                        </div>
                        @if (isset($consejeria_detalles) && isset($consejeria_detalles->deleted_us))
                          <div class="panel panel-danger panel-bordered panel-sm">
                            <div class="panel-body" style="background-color: #ffc107; border: 1px solid #dc3545">
                              <div class="col-md-4">
                                  <p><b>Motivo:</b> {{ $consejeria_detalles->deleted_reason}}</p>
                              </div>
                              <div class="col-md-4">
                                <p><b>Fecha de Anulación:</b> {{ $consejeria_detalles->deleted_at }}</p>
                              </div>
                              <div class="col-md-4">
                                <p><b>Usuario Invalidador:</b> {{ $consejeria_detalles->deleted_name }} {{ $consejeria_detalles->deleted_paterno }} {{ $consejeria_detalles->deleted_materno }}</p>
                              </div>
                            </div>
                          </div>
                        @endif
                        <div class="col-md-6">
                          <div class="panel panel-info contenedor-datos-generales" style="height:214px;">
                            <div class="panel-heading">
                              <h3 class="panel-title size-titulo">DATOS GENERALES DE LA ATENCIÓN</h3>
                            </div>
                            <div class="panel-body">
                              <table class="table table-borderless" style="width: auto; border: 0px solid;">
                                <tr class="texto-tamaño">
                                  <th style="text-align: left; border: 0px solid;width: 18%;"><strong>COD.SITEDS SUSALUD</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid; text-transform: uppercase; font-weight:bold;">
                                      <span id="cod_siteds_susalud">{{$cita->cod_siteds_susalud }}</span>
                                  </td>
                                  <th style="text-align: left; border: 0;width: 17%;"><strong>HORA DE ATENCIÓN</strong></th>
                                  <th style="text-align: left; border: 0;width: 1%;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                      <span id="hora_atencion">{{ \Carbon\Carbon::parse($consejeria_detalles->fecha_atencion)->format('H:i') }}</span>
                                  </td>
                                </tr>
                                <tr class="texto-tamaño">
                                  <th style="text-align: left; border: 0px solid;"><strong>TIPO DE ATENCIÓN</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                    <span id="tipo_atencion" style="font-weight:bold;">HOSPITALIZACION</span>
                                  </td>
                                </tr>
                                <tr class="texto-tamaño">
                                  <th style="text-align: left; border: 0px solid;"><strong>FECHA DE INGRESO</strong></th>
                                  <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                    <span id="fecha_ingreso">{{ \Carbon\Carbon::parse($hospitalizacion->fecha_ingreso)->format('d-m-Y g:i A') }}</span>
                                  </td>
                                </tr>
                                <tr class="texto-tamaño">
                                  <th style="text-align: left; border: 0;"><strong>FECHA DE ATENCIÓN</strong></th>
                                  <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                  <td style="border: 0px solid;">
                                    <span id="fecha_ingreso">{{ \Carbon\Carbon::parse($consejeria_detalles->fecha_atencion)->format('d-m-Y') }}</span>
                                  </td>
                                </tr>
                                <tr class="texto-tamaño">
                                  <th style="text-align: left; border: 0;"><strong>SERVICIO DE PROCEDENCIA</strong></th>
                                  <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                  <td style="border: 0;" colspan="4">
                                    <p>{{$upsses[0]->codigo}} - {{$upsses[0]->descripcion_upss}}</p>
                                  </td>
                                </tr>
                                @if (isset($cama) && isset($cama->cama))
                                   <tr class="texto-tamaño">
                                    <th style="text-align: left; border: 0;width:25%;"><strong>UBICACIÓN DE PACIENTE</strong></th>
                                    <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                    <input type="hidden" id="id_servicio_encargado" name="id_servicio_encargado" value="{{ $hospitalizacion->id_servicio_encargado }}" />
                                    <td colspan="5" style="border: 0; font-weight: bold;">
                                      {{ $cita->servicio }} / CAMA: {{ $cama->cama }}
                                    </td>
                                  </tr> 
                                @endif
                                
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="panel panel-info panel-bordered panel-sm" style="height:200px;">
                        <div class="panel-heading">
                          <div class="panel-title size-titulo">
                            <h3 class="panel-title size-titulo">DATOS DEL REGISTRO DE PROCEDIMIENTO</h3>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="panel-body">
                            <table class="table table-borderless" style="width: auto; border: 0px solid;">
                              <tr class="texto-tamaño">
                                <th style="text-align: left; border: 0px solid; width: 15%;"><strong>RESPONSABLE DE LA ATENCION</strong></th>
                                <th style="text-align: left; border: 0px solid; width:2%;"><strong>:</strong></th>
                                <td style="border: 0px solid;">
                                  <span id="tipo_doc_medico" type="hidden">
                                    <?php echo $cita->tipo_doc_ident ?> - <?php echo $cita->nro_dni_medico ?> / <?php echo trim("{$cita->medico_paterno} {$cita->medico_materno} {$cita->medico_nombre}"); ?>
                                  </span>
                                </td>
                              </tr>
                              <tr class="texto-tamaño">
                                <th style="text-align: left; border: 0px solid;"><strong>PROFESION/ESPECIALIDAD</strong></th>
                                <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                <td style="border: 0px solid;">
                                  <span id="profesion_medico" style="margin-left: -2px;">
                                    <?php echo $cita->profesion_medico?> / <?php echo $cita->especializacion_medico?>
                                  </span>
                                </td>
                              </tr>
                              <tr class="texto-tamaño">
                                <th style="text-align: left; border: 0px solid;"><strong>N° CMP / N° RNE</strong></th>
                                <th style="text-align: left; border: 0px solid;"><strong>:</strong></th>
                                <td style="border: 0px solid;" colspan="4">
                                    <span id="medico" style="margin-left: -2px;"><?php echo $cita->colegiatura_medico;?> / <?php echo $cita->rne_medico;?></span>
                                </td>
                              </tr>
                              <tr class="texto-tamaño">
                                <th style="text-align: left; border: 0;"><strong>DEPARTAMENTO</strong></th>
                                <th style="text-align: left; border: 0;"><strong>:</strong></th>
                                <td style="border: 0;" colspan="4">
                                  <p style="text-align: left;">{{ $consejeria_detalles->especialidad }}</p>
                                </td>
                                <th style="text-align: right; border: 0 solid;width:32%;"><strong>CODIGO UPSS ESPECIALIDAD</strong></th>
                                <th style="text-align: left; border: 0 solid;width:-20%;"><strong>:</strong></th>
                                <td style="border: 0; colspan="4">
                                  <div style="width: 100%;">
                                    <p>{{ $consejeria_detalles->upss_codigo }} - {{ $consejeria_detalles->descripcion_upss }}</p>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div> 
                      </div>
                      <fieldset>
                          <div class="panel panel-info panel-bordered panel-sm">
                              <div class="panel-heading">
                                <div class="panel-title size-titulo">
                                  <b>DIAGNÓSTICOS</b>
                                  <!--button type="button" class="btn btn-primary" id="btnEditDiagnostico" style="position: absolute; top:-6px; right: 0; display: none;">Editar</button-->
                                </div>
                              </div>
                              @if(count($diagnostico)>0)
                              <div class="panel-body">
                                  <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token_tag">
                                  <table class="display" id="tblDiagnostico" cellspacing="0" width="100%" style="margin-top:5px;">
                                      <thead>
                                          <tr>
                                          <th width="20%">Codigo</th>
                                          <th width="50%">Nombre</th>
                                          <th width="20%">Tipo</th>
                                          <th width="10%">Prioridad</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach($diagnostico as $row)
                                            @if ($row->id_clasificacion_diagnosticos != 9)
                                            <?php
                                              $tipoDiagnostico = '';
                                              switch($row->id_tipo_diagnostico) {
                                                case 1: $tipoDiagnostico = 'PRESUNTIVO'; break;
                                                case 2: $tipoDiagnostico = 'DEFINITIVO'; break;
                                                case 3: $tipoDiagnostico = 'REITERATIVO'; break;
                                                default:
                                              }
                                              $categoria = '';
                                              if ($row->id_categoria != null) {
                                                $categoria = ($row->id_categoria == 1) ? 'PRINCIPAL' : 'SECUNDARIO';
                                              }
                                              ?>
                                            <tr>
                                              <td>{{ $row->codigo }}</td>
                                              <td>{{ $row->nombre}}</td>
                                              <td>{{ $tipoDiagnostico }}</td>
                                              <td>{{ $categoria }}</td>
                                            </tr>
                                            @endif
                                          @endforeach
                                      </tbody>
                                  </table>
                              </div>
                              @endif
                      </fieldset>
                      <fieldset>
                          <div class="panel panel-info panel-bordered panel-sm">
                            <div class="panel-heading">
                                <div class="panel-title size-titulo">
                                  <b>PROCEDIMIENTOS</b>
                                    <button type="button" class="btn btn-primary" id="btnEditProcedimiento" style="position: absolute; top:-6px; right: 0;display:none;">Editar</button>
                                </div>
                            </div>
                            @if(count($procedimiento)>0)
                            <div class="panel-body">
                                <table class="display" id="tblProcedimiento" cellspacing="0" width="100%" style="margin-top:5px;">
                                    <thead>
                                    <tr>
                                        <th width="20%">Código</th>
                                        <th width="55%">Nombre</th>
                                        <th width="8%">Cantidad</th>
                                        <th width="8%">Tiempo</th>
                                        {{-- <th width="15%">Diagnóstico</th>  --}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($procedimiento as $row)
                                        <tr>
                                            <td>{{ $row->codigo }}</td>
                                            <td>{{ $row->nombre }}</td>
                                            <td>{{ $row->cantidad }}</td>
                                            <td>{{ $row->tiempo }}</td>
                                            {{-- <td>{{ $row->codigo_diagnostico }}</td>  --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                            <br/><br/>
                        </div>
                      </fieldset>

                      <div class="panel panel-info panel-bordered panel-sm">
                        <div class="panel-heading">
                          <div class="panel-title size-titulo">
                            <b>DESCRIPCIÓN</b>
                          </div>
                        </div>
                        <div class="panel-body">
                          <div class="form-group col-md-12">
                            <label for="descripcion">DESCRIPCIÓN</label><br>
                            <?php echo htmlspecialchars($consejeria->observacion); ?>
                          </div>
                        </div>
                      </div>
                     
                      {{-- @if (isset($ultima_atencion) && isset($ultima_atencion->id_prestacion) && intval($ultima_atencion->id_prestacion) > 0) --}}
                        @if ($consejeria->flag_firma)
                          <a href="" class="btn btn-sm btn-primary pull-right" target="_blank" id="btnShowPdfFirmado"><i class="fa fa-download" aria-hidden="true">VER PDF FIRMADO</i></a>
                        @else
                          <button type="button" class="btn btn-sm btn-primary pull-right" id="btnFirmar" onclick="firmarPDF()"><i class="fa fa-pencil"></i> FIRMAR</button>
                        @endif
                      {{-- @endif --}}
                      {{-- <a class="btn btn-primary btn-sm hidden-print pull-right" target="_blank" href="/consejeria/firmar/22"><span
                        class="fa fa-pencil"></span> &nbsp; FIRMAR</a>
                      </a> --}}
                      <a class="btn btn-primary btn-sm hidden-print pull-right" style="margin-right: 10px" href="/ver_prestaciones_consejeria/<?php echo $hospitalizacion->id ?>"><span
                        class="glyphicon glyphicon-list"></span> &nbsp; LISTADO</a>
                      </a>
                  </form>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="addComponent"></div>

@include('frontend.includes.footer')
@endsection