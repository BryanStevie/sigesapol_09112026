<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <title>RUA</title>
      <style type="text/css">
      html { font-family:Helvetica, sans-serif; font-size:10pt; background-color:white }
      div.comment { display:none }
      table { border-collapse:collapse; }
      .gridlines td { border:0.3px dotted black }
      .gridlines th { border:0.3px dotted black }
      .b { text-align:center }
      .e { text-align:center }
      .f { text-align:right }
      .inlineStr { text-align:left }
      .n { text-align:right }
      .s { text-align:left }
      td.style4 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style5 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style6 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style7 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style8 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style9 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:9pt; background-color:white }
      td.style10 { vertical-align:bottom; text-align:right; padding-right:0px; border-bottom:none #000000; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style11 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style12 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style13 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style14 { vertical-align:middle; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }
      td.style15 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style16 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style17 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style18 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style19 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style20 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style21 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style22 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style23 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style24 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style25 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#B4C6E7 }
      td.style26 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }
      td.style27 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:#B4C6E7 }
      td.style28 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#B4C6E7 }
      td.style29 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#FFFFFF }
      td.style30 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#FFFFFF }
      td.style31 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style32 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style33 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style34 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style35 { vertical-align:middle; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style36 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style37 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style38 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style39 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style40 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style41 { vertical-align:bottom; border-bottom:1px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style42 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style43 { vertical-align:middle; text-align:center; padding-right:0px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }
      td.style44 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style45 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:white }
      td.style46 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:10pt; background-color:white }
      td.style47 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style48 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style49 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style50 { vertical-align:bottom; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style51 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:16pt; background-color:white }
      td.style52 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style53 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style54 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style55 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style56 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style57 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style58 { vertical-align:bottom; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style59 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style60 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style61 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style62 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style63 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style64 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; text-decoration:underline; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style65 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; text-decoration:underline; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:white }
      td.style66 { vertical-align:bottom; border-bottom:1px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style67 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:9pt; background-color:white }
      td.style68 { vertical-align:middle; padding-left:5px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }
      td.style69 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:16pt; background-color:white }
      td.style70 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style71 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style72 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style73 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:white }
      td.style74 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }
      td.style75 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:#B4C6E7 }
      td.style76 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:#B4C6E7 }
      td.style77 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }
      td.style78 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:6pt; background-color:white }
      td.style79 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style80 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style81 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style82 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style83 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style84 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style85 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style86 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style87 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style88 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style89 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style90 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style91 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style92 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style93 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style94 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style95 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style96 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:9pt; background-color:#FFFF66 }
      td.style97 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#FFFF66 }
      td.style98 { vertical-align:bottom; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:9pt; background-color:#FFFF66 }
      td.style99 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style100 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style101 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style102 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style103 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style104 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style105 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style106 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style107 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style108 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style109 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style110 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style111 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style112 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style113 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style114 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style115 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style116 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#FFFFFF; font-family:'Helvetica'; font-size:11pt; background-color:#FF0000 }
      td.style117 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#FFFFFF; font-family:'Helvetica'; font-size:11pt; background-color:#FF0000 }
      td.style118 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style119 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style120 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:8pt; background-color:#C8C8C8 }
      td.style121 { vertical-align:middle; text-align:left; padding-left:5px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#FFFFFF; font-family:'Helvetica'; font-size:12pt; background-color:#404040 }
      td.style122 { vertical-align:middle; text-align:left; padding-left:5px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#FFFFFF; font-family:'Helvetica'; font-size:12pt; background-color:#404040 }
      td.style123 { vertical-align:middle; text-align:left; padding-left:5px; border-bottom:none #000000; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#FFFFFF; font-family:'Helvetica'; font-size:10pt; background-color:#BFBFBF }
      td.style124 { vertical-align:middle; text-align:left; padding-left:5px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#FFFFFF; font-family:'Helvetica'; font-size:10pt; background-color:#BFBFBF }
      td.style125 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style126 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style127 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:#C8C8C8 }
      td.style128 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:0.3px solid #000000 !important; border-right:none #000000; color:#FF0000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style129 { vertical-align:middle; text-align:center; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#FF0000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style130 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style131 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:0.3px solid #000000 !important; border-top:0.3px solid #000000 !important; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:12pt; background-color:white }
      td.style132 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:#9CC2E5 }
      td.style133 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:#9CC2E5 }
      td.style134 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:#92D050 }
      td.style135 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; font-weight:bold; color:#000000; font-family:'Helvetica'; font-size:14pt; background-color:#92D050 }
      td.style136 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
      td.style137 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:0.3px solid #000000 !important; color:#000000; font-family:'Helvetica'; font-size:11pt; background-color:white }
    </style>
  </head>

  <body>
<style>
@page { margin-left: 0.15in; margin-right: 0.15in; margin-top: 0.11in; margin-bottom: 0.11in; }
body { margin-left: 0.15in; margin-right: 0.15in; margin-top: 0.11in; margin-bottom: 0.11in; }
</style>
    <table border="0" cellpadding="0" cellspacing="0">
        <tbody>
          <tr class="row0">
            <td class="column0 style50">
<div style="position: relative;"><img style="position: absolute; z-index: 1; left: 0px; top: 0px; width: 65px; height: 56px;" src="/media/image1.png" border="0" /></div></td>
            <td class="column1 style116 s style117" rowspan="3">PERÚ</td>
            <td class="column2 style121 s style122" colspan="3" rowspan="3">Ministerio del Interior</td>
            <td class="column5 style123 s style124" colspan="5" rowspan="3">Fondo de Aseguramiento en Salud de la Policia Nacional del Perú - SALUDPOL</td>
            <td class="column10"></td>
            <td class="column11 style15"></td>
            <td class="column12 style15"></td>
            <td class="column13 style15"></td>
            <td class="column14 style15"></td>
            <td class="column15 style15"></td>
            <td class="column16 style15"></td>
            <td class="column17 style15"></td>
            <td class="column18 style15"></td>
            <td class="column19 style15"></td>
            <td class="column20 style15"></td>
            <td class="column21 style15"></td>
            <td class="column22 style15"></td>
            <td class="column23 style15"></td>
            <td class="column24 style15"></td>
            <td class="column25 style19 "></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row1">
            <td class="column0 style4 "></td>
            <td class="column10 style51 "></td>
            <td class="column11 style72 s style73" colspan="15" rowspan="2">REGISTRO UNICO DE ATENCIÓN - RUA CONSULTORIOS HOSPITAL PNP</td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row2">
            <td class="column0 style4 "></td>
            <td class="column10 style2 "></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row4">
            <td class="column0 style87 s style91" colspan="3" rowspan="2">HOSPITAL: _____________</td>
            <td class="column3 style102 s style104" colspan="4">CODIGO RENIPRESS</td>
            <td class="column7 style102 s style104" colspan="4">FECHA DE ATENCION</td>
            <td class="column11 style102 s style104" colspan="2">PRIORIDAD</td>
            <td class="column13 style102 s style104" colspan="5">N° DE RUA</td>
            <td class="column18 style102 s style104" colspan="8">REGION</td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row5">
            <td class="column3 style128  style129" colspan="4"></td>
            <td class="column7 style93  style94" colspan="4"></td>
            <td class="column11 style93  style95" colspan="2"></td>
            <td class="column13 style93  style95" colspan="5"></td>
            <td class="column18 style93  style95" colspan="8"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row6">
            <td class="column0 style102 s style104">DNI</td>
            <td class="column1 style34" colspan="2"></td>
            <td class="column3 style34 "></td>
            <td class="column4 style35 "></td>
            <td class="column5 style102 s style104" colspan="6">NOMBRES Y APELLIDOS</td>
            <td class="column11 style102 s style104" colspan="2">SEXO</td>
            <td class="column13 style102 s style104" colspan="2">EDAD</td>
            <td class="column15 style102 s style104" colspan="2">GRADO</td>
            <td class="column17 style93  style95" colspan="2"></td>
            <td class="column19 style102 s style104" colspan="3">CONDICION</td>
            <td class="column22 style47 s">T</td>
            <td class="column23 style47 s">C</td>
            <td class="column24 style47 s">H</td>
            <td class="column25 style47 s">P</td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row7">
            <td class="column0 style102 s style104">HC</td>
            <td class="column1 style31 " colspan="2"></td>
            <td class="column3 style31 "></td>
            <td class="column4 style32 "></td>
            <td class="column5 style93  style95" colspan="6"></td>
            <td class="column11 style47 s" colspan="2">M/F</td>
            <td class="column13 style93  style95" colspan="2"></td>
            <td class="column15 style102 s style104" colspan="4">PROXIMA CITA</td>
            <td class="column19 style102 s style104" colspan="3">FECHA DE TOMA</td>
            <td class="column22 style99  style101" colspan="4"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row8">
            <td class="column0 style102 s style104">SERVICIO</td>
            <td class="column1 style31 " colspan="2"></td>
            <td class="column3 style31 "></td>
            <td class="column4 style31 "></td>
            <td class="column5 style113 s style120" colspan="2">DIAGNOSTICOS:</td>
            <td class="column7 style130 s style131" colspan="4"></td>
            <td class="column11 style113 s style115" colspan="2">CIE-10</td>
            <td class="column13 style93  style95" colspan="2"></td>
            <td class="column15 style93  style95" colspan="4"></td>
            <td class="column19 style102 s style104" colspan="3">HORA DE TOMA</td>
            <td class="column22 style99  style101" colspan="4"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row10">
            <td class="column0 style96 s style98" colspan="26">SOLICITUD DE ANALISIS CLINICOS</td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <!--inicio-->
          <tr class="row11">
            <!-- inicio columna columna!-->
            <?php $fila=1; $ultimo=0; $inicio=1; $titulo=''; $flag=0; ?>
            @for($col=1;$col<5;$col++)
                <?php 
                  switch($col){
                    case 1: echo '<td class="column0 style26 s" colspan="4">'; break;
                    case 2: echo '<td class="column0 style26 s" colspan="4">'; break;
                    case 3: echo '<td class="column0 style26 s" colspan="9">'; break;
                    case 4: echo '<td class="column0 style26 s" colspan="9">'; break;
                  }
            ?>
                
            @foreach($lista_rua as $key=>$e)
              <?php
                
                if($fila>45){
                  $fila=1; $ultimo=$inicio-1; $inicio=1;
                  break;
                }
              ?>    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tbody>
                        <?php 
                          if($inicio>$ultimo){
                            if($e->nombre!=$titulo){
                              $titulo=$e->nombre;
                              $flag=1; 
                              $fila++;
                            
                        ?>
                            <tr class="row11">
                              <td width="10%" class="column0 style26 s">CPT</td>
                              <td width="5%" class="column1 style26 "></td>
                              <td width="85%" class="column2 style74 s style76" colspan="11">{{$e->nombre}}</td>
                            </tr>
                            <tr class="row11">
                                <td width="10%" class="column0 style43 n">{{$e->codigo}}</td>
                                <td width="5%" class="column1 style13"></td>
                                <td width="85%" class="column2 style14 s" colspan="11">&nbsp;&nbsp;{{$e->descripcion_corta}}</td>
                            </tr>
                        <?php  
                            
                            }
                            else{
                        ?>
                            <tr class="row11">
                                <td width="10%" class="column0 style43 n">{{$e->codigo}}</td>
                                <td width="5%" class="column1 style13"></td>
                                <td width="85%" class="column2 style14 s" colspan="11">&nbsp;&nbsp;{{$e->descripcion_corta}}</td>
                            </tr>
                        <?php
                               
                            }
                            $fila++;
                        ?>
                        <?php
                          }
                          $inicio++;
                    ?>
                      </tbody>
                    </table>
                  @endforeach
                @endfor
                </td>
            <!--final columna-->
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <!--fin-->
          <tr class="row34">
            <td class="column0 style87 s style89" colspan="9">PROFESIONAL DE LA SALUD RESPONSABLE DE LA ATENCIÓN</td>
            <td class="column9 style113 s style115" colspan="8">ANALISIS SOLICITADOS</td>
            <td class="column17 style81 s style83" colspan="9">BENEFICIARIO, PADREO O APODERADO</td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row35">
            <td class="column0 style87 s style92" colspan="2" rowspan="4">FIRMA Y SELLO</td>
            <td class="column2 style107 null style112" colspan="7" rowspan="4"></td>
            <td class="column9 style102 s style104" colspan="8">NUMERO:</td>
            <td class="column17 style87 s style92" colspan="3" rowspan="2">FIRMA Y Huella</td>
            <td class="column20 style36 null"></td>
            <td class="column21 style59 null"></td>
            <td class="column22 style59 null"></td>
            <td class="column23 style59 null"></td>
            <td class="column24 style59 null"></td>
            <td class="column25 style37 null"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row36">
            <td class="column9 style99 null style101" colspan="8"></td>
            <td class="column20 style38 null"></td>
            <td class="column21 style60 null"></td>
            <td class="column22 style60 null"></td>
            <td class="column23 style60 null"></td>
            <td class="column24 style60 null"></td>
            <td class="column25 style39 null"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row37">
            <td class="column9 style102 s style104" colspan="8">LETRAS:</td>
            <td class="column17 style81 s style83" colspan="3">Nombres y Apellidos:</td>
            <td class="column20 style84 null style86" colspan="6"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row38">
            <td class="column9 style99 null style101" colspan="8"></td>
            <td class="column17 style81 s style83" colspan="3">DNI ó CE:</td>
            <td class="column20 style61 null"></td>
            <td class="column21 style62 null"></td>
            <td class="column22 style62 null"></td>
            <td class="column23 style62 null"></td>
            <td class="column24 style62 null"></td>
            <td class="column25 style63 null"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row39">
            <td class="column0 style68 s" colspan="25">*DOCUMENTO MEDICO LEGAL PARA AUDITORIA / *ES RESPONSABILIDAD DEL MÉDICO TRATANTE EL LLENADO COMPLETO Y LEGIBLE DE LA HOJA RUA.</td>
            <td class="column25 style19 null"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          <tr class="row40">
            <td class="column0 style52 null"></td>
            <td class="column1 style21 null"></td>
            <td class="column2 style21 null"></td>
            <td class="column3 style21 null"></td>
            <td class="column4 style21 null"></td>
            <td class="column5 style21 null"></td>
            <td class="column6 style21 null"></td>
            <td class="column7 style21 null"></td>
            <td class="column8 style21 null"></td>
            <td class="column9 style21 null"></td>
            <td class="column10 style21 null"></td>
            <td class="column11 style21 null"></td>
            <td class="column12 style21 null"></td>
            <td class="column13 style21 null"></td>
            <td class="column14 style21 null"></td>
            <td class="column15 style21 null"></td>
            <td class="column16 style21 null"></td>
            <td class="column17 style21 null"></td>
            <td class="column18 style21 null"></td>
            <td class="column19 style21 null"></td>
            <td class="column20 style21 null"></td>
            <td class="column21 style21 null"></td>
            <td class="column22 style21 null"></td>
            <td class="column23 style21 null"></td>
            <td class="column24 style21 null"></td>
            <td class="column25 style24 null"></td>
            <td class="column26">&nbsp;</td>
            <td class="column27">&nbsp;</td>
            <td class="column28">&nbsp;</td>
            <td class="column29">&nbsp;</td>
          </tr>
          
        </tbody>
    </table>
  </body>
</html>
