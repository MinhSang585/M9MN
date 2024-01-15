var _rules = {
	register: {
		rules: {
			fr_first_name: {
				required: true,
				alpha_numeric_firstname: true,
			},
			fr_nickname: {
				required: true,
				minlength: 6,
				alpha_numericRegex: true,
			},
			fr_username: {
				required: true,
				minlength: 6,
				alpha_numericRegex: true,
				checkUserName: true,
			},
			fr_upline: {
				alpha_numericRegex: true,
			},
			fr_password: {
				required: true,
				minlength: 6,
				alpha_numeric_symbolRegex: true,
			},
			fr_password2: {
				required: true,
				minlength: 6,
				equalTo: "#fr_new_password",
			},
			fr_email: {
				required: true,
				email: true,
				email_format: true,
			},
			fr_contact_number: {
				required: true,
				number: true,
			},
			fr_lineid: {
				alpha_numericLINERegex: true,
			},
			fr_gdcode: {
				required: true,
				number: true,
				minlength: 4,
			},
		},
		messages: {
			fr_first_name: _lang.rg_val_fullname,
			fr_nickname: {
				required: _lang.rg_val_nickname,
				minlength: jQuery.format(_lang.err_min_length),
				alpha_numericRegex: _lang.rg_val_nickname_alphanumeric,
			},
			fr_username: {
				required: _lang.rg_val_username,
				minlength: _lang.rg_val_minlength,
				alpha_numericRegex: _lang.rg_val_username_alphanumeric,
				checkUserName: _lang.fp_val_username_inuse,
			},
			fr_upline: {
				alpha_numericRegex: _lang.rg_val_upline_alphanumeric,
			},
			fr_password: {
				required: _lang.rg_val_password,
				minlength: _lang.rg_val_minlength,
				alpha_numericRegex: _lang.rg_val_password_alphanumeric,
				alpha_numeric_symbolRegex: _lang.rg_val_password_alphanumeric_symbol,
			},
			fr_password2: {
				required: _lang.rg_val_repeat_password,
				minlength: _lang.rg_val_minlength,
				equalTo: _lang.rg_val_repeat_password_equal,
			},
			fr_email: {
				required: _lang.rg_val_email,
				minlength: _lang.rg_val_email,
				email: _lang.rg_val_email,
				email_format: _lang.err_format,
				remote: _lang.rg_val_email_inuse,
			},
			fr_contact_number: {
				required: _lang.rg_val_contact_number,
				number: _lang.rg_val_contact_number_numeric,
				minlength: _lang.rg_val_number_minlength,
				remote: _lang.rg_val_contact_number_inuse,
			},
			fr_lineid: {
				alpha_numericLINERegex: _lang.rg_val_line_id_alphanumeric,
			},
			fr_gdcode: {
				required: _lang.rg_val_gdcode,
				number: _lang.rg_val_gdcode_number,
				minlength: _lang.rg_val_minlength,
			},
		}, //end messages
	}, //end register
	chgPassword: {
		rules: {
			oldPass: {
				required: true,
				minlength: 6,
				alpha_numeric_symbolRegex: true,
			},
			newPass: {
				required: true,
				minlength: 6,
				alpha_numeric_symbolRegex: true,
			},
			newPassC: {
				required: true,
				minlength: 6,
				equalTo: "#newPass",
			},
		},
		messages: {
			oldPass: {
				required: _lang.err_pswd_required,
				minlength: jQuery.format(_lang.err_pswd_min_length),
				alpha_numericRegex: _lang.err_pswd_format,
				alpha_numeric_symbolRegex: _lang.err_pswd_format_symbol,
			},
			newPass: {
				required: _lang.err_pswd_required,
				minlength: jQuery.format(_lang.err_pswd_min_length),
				alpha_numericRegex: _lang.err_pswd_format,
				alpha_numeric_symbolRegex: _lang.err_pswd_format_symbol,
			},
			newPassC: {
				required: _lang.err_pswd_required,
				minlength: jQuery.format(_lang.err_pswd_min_length),
				equalTo: _lang.err_re_pswd_match,
			},
		},
	}, //end changePassword
	profile: {
		rules: {
			fr_nickname: {
				required: true,
				minlength: 6,
				alpha_numericRegex: true,
			},
		},
		messages: {
			fr_nickname: {
				required: _lang.err_empty_txtbox,
				minlength: jQuery.format(_lang.err_min_length),
				alpha_numericRegex: _lang.rg_val_nickname_alphanumeric,
			},
		},
	}, //end profile
	transfer: {
		rules: {
			t_from: {
				required: true,
			},
			t_to: {
				required: true,
			},
			t_amount: {
				required: true,
				minlength: 1,
				number: true,
				decimal: true,
			},
		},
		messages: {
			t_from: _lang.err_empty_select,
			t_to: _lang.err_empty_select,
			t_amount: {
				required: _lang.err_empty_txtbox,
				minlength: _lang.err_format,
				number: _lang.err_numeric_value,
				decimal: _lang.err_decimal_length,
			},
		},
	}, //end transfer
	deposit: {
		rules: {
			depAmount: {
				required: true,
				number: true,
			},
			bankId: "required",
			pgBankId: "required",
			pgBankOption: "required",
			depMethodType: "required",
			depoDateTime: "required",
		},
		messages: {
			depAmount: {
				required: _lang.err_empty_txtbox,
				number: _lang.err_numeric_value,
			},
			bankId: {
				required: _lang.err_empty_select,
			},
			pgBankId: {
				required: _lang.err_empty_select,
			},
			pgBankOption: {
				required: _lang.err_empty_select,
			},
			depMethodType: {
				required: _lang.err_empty_select,
			},
			depoDateTime: {
				required: _lang.err_empty_deposit_date,
			},
		},
	}, //end deposit
	forgetPass: {
		rules: {
			fr_username: {
				required: true,
				minlength: 6,
			},
			fr_email: {
				required: true,
				email: true,
			},
		}, //end rules
		messages: {
			fr_username: {
				required: _lang.fp_val_username,
				minlength: _lang.fp_val_minlength,
				remote: _lang.fp_val_username_inuse,
			},
			fr_email: {
				required: _lang.fp_val_email,
				minlength: _lang.fp_val_email,
				email: _lang.fp_val_email,
				remote: _lang.fp_val_email_inuse,
			},
		}, //end message
	}, //forgetPass
	withdrawal: {
		rules: {
			withAmount: {
				required: true,
				number: true,
			},
			bankAccId: {
				required: function () {
					if ($("#newbankgroup").length > 0) {
						if ($("#newbankgroup").is(":visible")) {
							return false;
						}
					}
					return true;
				},
			},
			baBankId: {
				required: true,
			},
			baNo: {
				required: true,
				number: true,
			},
		},
		messages: {
			withAmount: {
				required: _lang.err_empty_txtbox,
				number: _lang.err_numeric_value,
			},
			bankAccId: _lang.err_empty_select,
			baBankId: _lang.err_empty_select,
			baNo: {
				required: _lang.err_empty_txtbox,
				number: _lang.err_numeric_value,
			},
		},
	}, //end withdrawal
};

//extra country settings
if (["CN", "TH", "TW", "VN"].indexOf(country) > -1) {
	console.log("yes");
	delete _rules.register.alpha_numeric_firstname;
}
