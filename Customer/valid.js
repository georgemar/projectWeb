function validateNew() {
  var mail = document.forms["newuser"]["email"].value;
  var pass = document.forms["newuser"]["pass"].value;
  var tel = document.forms['newuser']['tel'].value;
  var format = /[\s\S]*@[\s\S]*\.[a-zA-Z]+/;
  if (!format.test(mail)) {
    document.getElementById('wmailn').style.display = 'inline';
    return false;
  } else {
    document.getElementById('wmailn').style.display = 'none';
  }
  if (/;/.test(pass) || pass.length < 8 || pass.length > 32) {
    document.getElementById('wpassn').style.display = 'inline';
    return false;
  } else {
    document.getElementById('wpassn').style.display = 'none';
  }
  if (/;/.test(tel) || tel.length != 10) {
    document.getElementById('wteln').style.display = 'inline';
    return false;
  } else {
    document.getElementById('wteln').style.display = 'none';
  }
  return true;
}

function validateExist() {
  var mail = document.forms['existuser']['email'].value;
  var pass = document.forms['existuser']['pass'].value
  var format = /[\s\S]*@[\s\S]*\.[a-zA-Z]+/;
  if (!format.test(mail)) {
    document.getElementById('wmaile').style.display = 'inline';
    return false;
  } else {
    document.getElementById('wmaile').style.display = 'none';
  }
  if (/;/.test(pass) || pass.length < 8 || pass.length > 32) {
    document.getElementById('wpasse').style.display = 'inline';
    return false;
  } else {
    document.getElementById('wpasse').style.display = 'none';
  }
  return true;
}
