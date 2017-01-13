'use strict';
(function(){$(document).ready(function(){
	var rootIpt=$('form.form-login > #login-root');
	var signupPwd = $('form.form-signup > input[type="password"]');
	$('form.form-login > input[type="submit"], form.form-signup > input[type="submit"]')
		.on('click',function(e){ console.log(rootIpt.val())});
	$('form.form-signup > input[type="submit"]')
		.on('submit',function(e){ if (signupPwd.get(0).val()!==signupPwd.get(1).val()) { 
			e.preventDefault(); e.stopPropagation(); 
			signupPwd.css('background-color','red');
			return false;
		}; });
});})()