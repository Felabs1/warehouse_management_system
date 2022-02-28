function openModal(id) {
  $(`#${id}`).show();
}

function printDocument(id) {
  // console.log("connected");
  var printContent = document.getElementById(id);
  var WinPrint = window.open("", "", "width=900, height=650");
  WinPrint.document.write(`<!DOCTYPE html>
  <html lang="en">
  
  <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Lost / Recovered Item Reports</title>
        
      <style>
      th,
      td {
          border: 1px solid #ccc;
          padding: 10px;
      }
      table{
        width: 100%;
        border-collapse: collapse;
      }
      </style>
      <link rel="stylesheet" type="text/css" href="./css/w3.css" />
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
  </head>
  
  <body class="w3-container w3-padding">${printContent.innerHTML}`);
  WinPrint.focus();
  WinPrint.print();
  WinPrint.close();
}
