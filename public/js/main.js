$(function() {
	$('#registerForm').bootstrapValidator({
        excluded: ':disabled',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            first_name: {
                validators: {
                    notEmpty: {
                        message: 'The First Name is required'
                    }
                }
            },
            last_name: {
                validators: {
                    notEmpty: {
                        message: 'The Last Name is required'
                    }
                }
            },
            title: {
                validators: {
                    notEmpty: {
                        message: 'The Title is required'
                    }
                }
            },
            company: {
                validators: {
                    notEmpty: {
                        message: 'The Company is required'
                    }
                }
            },
            address: {
                validators: {
                    notEmpty: {
                        message: 'The Last Name is required'
                    }
                }
            },
            city: {
                validators: {
                    notEmpty: {
                        message: 'The Address is required'
                    }
                }
            },
            state: {
                validators: {
                    notEmpty: {
                        message: 'The State is required'
                    }
                }
            },
            postcode: {
                validators: {
                    notEmpty: {
                        message: 'The Postcode is required'
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: 'The Country is required'
                    }
                }
            },
            phone: {
                validators: {
                    notEmpty: {
                        message: 'The Phone is required'
                    }
                }
            },
            fax: {
                validators: {
                    notEmpty: {
                        message: 'The Fax is required'
                    }
                }
            },
            email: {
                validators: {
                    notEmpty: {
                        message: 'The Email is required'
                    },
                    emailAddress: {
                        message: 'The Email is not a valid'
                    }
                }
            },
            payment_to: {
                validators: {
                    notEmpty: {
                        message: 'The Payment to is required'
                    }
                }
            },
            site_name: {
                validators: {
                    notEmpty: {
                        message: 'The Site Name is required'
                    }
                }
            },
            site_url: {
                validators: {
                    notEmpty: {
                        message: 'The Site URL is required'
                    },
                    uri: {
                        message: 'The Site URL is not valid'
                    }
                }
            },
            site_description: {
                validators: {
                    notEmpty: {
                        message: 'The Site Description is required'
                    }
                }
            },
            unique_visitor: {
                validators: {
                    notEmpty: {
                        message: 'The Monthly Unique Visitors is required'
                    }
                }
            },
            pageview: {
                validators: {
                    notEmpty: {
                        message: 'The Monthly Page Views is required'
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: 'The Category is required'
                    }
                }
            },
            'languages[]': {
                validators: {
                    choice: {
                        min: 1,
                        max: 3,
                        message: 'Please choose 1 - 3 languages'
                    }
                }
            },
            traffic_report_file: {
                validators: {
                    notEmpty: {
                        message: 'The Traffic Report File is required'
                    },
                    file: {
                        extension: 'pdf,jpeg,jpg,png,gif',
                        type: 'application/pdf,image/jpeg,image/png,image/gif',
                        message: 'The selected file is not valid'
                    }
                }
            },
            'reason[]': {},
            othercategory:{},
            reasonblog:{},
            reasonother:{},            
            agree: {
                validators: {
                    notEmpty: {
                        message: 'Please accept our Terms and Conditions'
                    }
                }
            },
        }
    });

	$('#resetBtn').click(function() {
	    $('#registerForm').data('bootstrapValidator').resetForm(true);
	});
	

	$.scrollify({
		section:".panel"
	});
	

	$(".scroll").click(function(e) {
		e.preventDefault();
		$.scrollify("move",$(this).attr("href"));
	});
});