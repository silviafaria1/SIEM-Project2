/*------------- Esconder botões ----------*/
function hide(id){
  var x = document.getElementById(id);

  if (x.style.opacity === "0") {
    x.style.opacity = 1;
  } else {
    x.style.opacity = 0;
  }
}

/* ----------------- Lista Pizza Individual -----------------*/
function clearCheckBox(id){
  var checkBox = document.getElementById(id);

  if (checkBox.checked == true){
    checkBox.checked=false;
  }
}

function updatePrecoQuantidade(preco_pizza){
  var preco_final;
  var preco_ingredientes= parseFloat(getCookie("preco_ingredientes"));
  // Get the quantidade
  var quantidade = document.getElementById("quantidade");

  quantidade=quantidade.value;

  preco_final=( preco_pizza+preco_ingredientes ) *quantidade;


  writePreco(preco_final);
  setCookie("preco_final", preco_final,1);
}

function updatePreco(preco,id, preco_pizza){
  var preco_ingredientes= parseFloat(getCookie("preco_ingredientes"));

  // Get the checkbox
  var checkBox = document.getElementById(id);

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    preco_ingredientes+=parseFloat(preco);
  } else {
    preco_ingredientes-=parseFloat(preco);
  }
  setCookie("preco_ingredientes",preco_ingredientes,1);

  updatePrecoQuantidade(preco_pizza,id);
}

function writePreco(preco){
  preco= (Math.round(preco * 100) / 100).toFixed(2);
  document.getElementById("preco_final").innerHTML = "PREÇO FINAL: "+preco+" €";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


/* ESCONDER TABELAS ESTATISTICAS */
function show_table(id){

  var x = document.getElementById(id);

  if (x.style.display === "block") {
      x.style.display = "none";
  } else {
      x.style.display = "block";
  }
} 
