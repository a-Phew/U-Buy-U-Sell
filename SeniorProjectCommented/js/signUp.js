  
  // function to check if password and confirm password match
  function checkPassword(form){
    let pwd1 = form.pwd1.value;
    let pwd2 = form.pwd2.value;

    if(pwd1 != pwd2){
      alert("Passwords do not match");
      return false;
    }
    else{
      return true;
    }

  }