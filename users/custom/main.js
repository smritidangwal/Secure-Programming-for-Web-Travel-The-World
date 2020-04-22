$(function () {

    var alphaRegex = /^[a-zA-Z ]+$/;
    var alphaNumericRegex = /^[a-zA-Z0-9]+$/;
    var emailRegex = /(^\w.*@\w+\.\w)/;
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*~\'\";:\?\\|\/.,_+-]).{8,}$/;
    var bioRegex = /^[a-zA-Z0-9 .,-]*$/;
    var maxImageSize = 2097152; // 2MB

    activateSummerNote();

    $('#headerImage').change(function () {
        if (this.files && this.files[0]) {
            var selectedFile = this.files[0];
            var selectedImgTag = $("#headerImageShow");
            var imageReader = new FileReader();
            var t = selectedFile.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png") {
                toastr.error('Please select a valid image file. Only JPG and PNG files are accepted.');
                $(this).val('');
                selectedImgTag.attr('src', '');
                return 0;
            }
            if (selectedFile.size > maxImageSize) {
                toastr.error('File upload error. Max upload size allowed is ' + maxImageSize / 1048576 + 'MB.');
                $(this).val('');
                selectedImgTag.attr('src', '');
                return 0;
            } else {
                imageReader.onload = function (e) {
                    selectedImgTag.attr('src', e.target.result);
                }
                imageReader.readAsDataURL(selectedFile);
            }
        }
    });

    $('#postForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submitAddPostForm);
        var thisForm = $(this);
        if (!$(this.postTitle).val()) {
            toastr.error('Enter post title.');
            $(this.postTitle).focus();
            return;
        }
        if (!$(this.postDescrp).val()) {
            toastr.error('Enter post description.');
            $(this.postDescrp).focus();
            return;
        }
        if ($('#postCont').summernote('isEmpty')) {
            toastr.error("Content editor is empty");
            return;
        }
        if (!$(this.headerImage).val()) {
            toastr.error('Please select a header image for your post.');
            $(this.headerImage).focus();
            return;
        }
        if (!validateFileUpload('headerImage', maxImageSize)) {
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false
        }).done(function (response) {
            response = JSON.parse(response);
            if (response.error === 0) {
                toastr.success(response.responseText);
                $('#postCont').summernote('code', "");
                $('#headerImageShow').attr('src', '');
                thisForm[0].reset();
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            submitBtn.prop('disabled', false);
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#addAdminForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submitAddPostForm);
        var thisForm = $(this);
        if (!$(this.fName).val()) {
            toastr.error('Enter first name.');
            $(this.fName).focus();
            return;
        } else if (!alphaRegex.test($(this.fName).val())) {
            toastr.error('Invalid characters found in first name.');
            $(this.fName).focus();
            return;
        }
        if (!$(this.lName).val()) {
            toastr.error('Enter last name.');
            $(this.lName).focus();
            return;
        } else if (!alphaRegex.test($(this.lName).val())) {
            toastr.error('Invalid characters found in last name.');
            $(this.lName).focus();
            return;
        }
        if (!$(this.mailId).val()) {
            toastr.error('Enter your email.');
            $(this.mailId).focus();
            return;
        } else if (!emailRegex.test($(this.mailId).val())) {
            toastr.error('Invalid characters/format found in email.');
            $(this.mailId).focus();
            return;
        }
        if (!$(this.userName).val()) {
            toastr.error('Enter username.');
            $(this.userName).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.userName).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.userName).focus();
            return;
        }
        if (!$(this.password).val()) {
            toastr.error('Enter password.');
            $(this.password).focus();
            return;
        } else if (!passwordRegex.test($(this.password).val())) {
            toastr.error('Password must have atleast one capital, one small, one number with minimum 8 characters.');
            $(this.password).focus();
            return;
        }
        if (!$(this.confrmPassword).val()) {
            toastr.error('Enter confirm password.');
            $(this.confrmPassword).focus();
            return;
        } else if ($(this.password).val() !== $(this.confrmPassword).val()) {
            toastr.error('Passwords do not match.');
            $(this.confrmPassword).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
                thisForm[0].reset();
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            submitBtn.prop('disabled', false);
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#adminLoginForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = this;
        if (!$(this.formUsername).val()) {
            toastr.error('Enter username.');
            $(this.formUsername).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.formUsername).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.formUsername).focus();
            return;
        }
        if (!$(this.formPassword).val()) {
            toastr.error('Enter password.');
            $(this.formPassword).focus();
            return;
        } else if (!passwordRegex.test($(this.formPassword).val())) {
            toastr.error('Invalid username or password. Please try again.');
            $(this.formPassword).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success('Login successful. Redirecting..');
                $(this).delay(500).queue(function () {
                    window.location.replace('./index.php');
                    return;
                });
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            grecaptcha.reset();
            submitBtn.prop('disabled', false);
            $(thisForm.formPassword).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#loginForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = this;
        if (!$(this.formUsername).val()) {
            toastr.error('Enter username.');
            $(this.formUsername).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.formUsername).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.formUsername).focus();
            return;
        }
        if (!$(this.formPassword).val()) {
            toastr.error('Enter password.');
            $(this.formPassword).focus();
            return;
        } else if (!passwordRegex.test($(this.formPassword).val())) {
            toastr.error('Invalid username or password. Please try again.');
            $(this.formPassword).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success('Login successful. Redirecting..');
                $(this).delay(500).queue(function () {
                    window.location.replace('./index.php');
                    return;
                });
                submitBtn.prop('disabled', false);
            } else if (response.error === 1) {
                toastr.error(response.responseText);
                submitBtn.prop('disabled', false);
            } else if (response.error === 7) {
                toastr.error(response.responseText);
                if (response.code == 1) {
                    grecaptcha.reset();
                } else {
                    grecaptcha.render('recaptcha', {
                        "callback": captchaCallback
                    });
                }
                submitBtn.prop('disabled', true);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
                submitBtn.prop('disabled', false);
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
                submitBtn.prop('disabled', false);
            }
            $(thisForm.formPassword).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#updateProfileForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submitUpdateProfileForm);
        var thisForm = $(this);
        if (!$(this.fName).val()) {
            toastr.error('Enter first name.');
            $(this.fName).focus();
            return;
        } else if (!alphaRegex.test($(this.fName).val())) {
            toastr.error('Invalid characters found in first name.');
            $(this.fName).focus();
            return;
        }
        if (!$(this.lName).val()) {
            toastr.error('Enter last name.');
            $(this.lName).focus();
            return;
        } else if (!alphaRegex.test($(this.lName).val())) {
            toastr.error('Invalid characters found in last name.');
            $(this.lName).focus();
            return;
        }
        if (!$(this.userName).val()) {
            toastr.error('Enter username.');
            $(this.userName).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.userName).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.userName).focus();
            return;
        }
        if ($(this.bio).val() && !bioRegex.test($(this.bio).val())) {
            toastr.error('Invalid characters found in bio. It can only have alphanumeric characters along with dot(.), comma(,) and hyphen(-).');
            $(this.bio).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            submitBtn.prop('disabled', false);
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#userRegistration').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = $(this);
        if (!$(this.fName).val()) {
            toastr.error('Enter first name.');
            $(this.fName).focus();
            return;
        } else if (!alphaRegex.test($(this.fName).val())) {
            toastr.error('Invalid characters found in first name.');
            $(this.fName).focus();
            return;
        }
        if (!$(this.lName).val()) {
            toastr.error('Enter last name.');
            $(this.lName).focus();
            return;
        } else if (!alphaRegex.test($(this.lName).val())) {
            toastr.error('Invalid characters found in last name.');
            $(this.lName).focus();
            return;
        }
        if (!$(this.mailId).val()) {
            toastr.error('Enter your email.');
            $(this.mailId).focus();
            return;
        } else if (!emailRegex.test($(this.mailId).val())) {
            toastr.error('Invalid characters/format found in email.');
            $(this.mailId).focus();
            return;
        }
        if (!$(this.userName).val()) {
            toastr.error('Enter username.');
            $(this.userName).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.userName).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.userName).focus();
            return;
        }
        if (!$(this.password).val()) {
            toastr.error('Enter password.');
            $(this.password).focus();
            return;
        } else if (!passwordRegex.test($(this.password).val())) {
            toastr.error('Password must have atleast one capital, one small, one number with minimum 8 characters.');
            $(this.password).focus();
            return;
        }
        if (!$(this.confrmPassword).val()) {
            toastr.error('Enter confirm password.');
            $(this.confrmPassword).focus();
            return;
        } else if ($(this.password).val() !== $(this.confrmPassword).val()) {
            toastr.error('Passwords do not match.');
            $(this.confrmPassword).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success('Registration successful. Verify your account by clicking the activation link sent on your email and start blogging.');
                thisForm[0].reset();
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            grecaptcha.reset();
            submitBtn.prop('disabled', false);
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#changePasswordForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submitChangePasswordForm);
        var thisForm = $(this);
        if (!$(this.oldPassword).val()) {
            toastr.error('Enter old password.');
            $(this.oldPassword).focus();
            return;
        } else if (!passwordRegex.test($(this.oldPassword).val())) {
            toastr.error('Invalid password format in old password. It should have atleast one capital, one small, one number with minimum 8 characters.');
            $(this.oldPassword).focus();
            return;
        }
        if (!$(this.password).val()) {
            toastr.error('Enter new password.');
            $(this.password).focus();
            return;
        } else if (!passwordRegex.test($(this.password).val())) {
            toastr.error('Invalid password format in new password. It should have atleast one capital, one small, one number with minimum 8 characters.');
            $(this.password).focus();
            return;
        }
        if (!$(this.confrmPassword).val()) {
            toastr.error('Enter confirm password.');
            $(this.confrmPassword).focus();
            return;
        } else if (!passwordRegex.test($(this.confrmPassword).val())) {
            toastr.error('Invalid password format in confirm password. It should have atleast one capital, one small, one number with minimum 8 characters and must match with new password.');
            $(this.confrmPassword).focus();
            return;
        } else if ($(this.password).val() !== $(this.confrmPassword).val()) {
            toastr.error('Passwords do not match.');
            $(this.confrmPassword).focus();
            return;
        } else if ($(this.password).val() == $(this.oldPassword).val()) {
            toastr.error('Old password and new password cannot be same.');
            $(this.password).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
                thisForm[0].reset();
                submitBtn.prop('disabled', false);
            } else if (response.error === 1) {
                toastr.error(response.responseText);
                submitBtn.prop('disabled', false);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
                submitBtn.prop('disabled', false);
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
                submitBtn.prop('disabled', false);
            }
            $(thisForm.formPassword).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#forgotPasswordForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = this;
        if (!$(this.formUsername).val()) {
            toastr.error('Enter username.');
            $(this.formUsername).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.formUsername).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.formUsername).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            grecaptcha.reset();
            submitBtn.prop('disabled', false);
            $(thisForm.formUsername).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#forgotPasswordCompleteForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = this;
        if (!$(this.x).val() || !$(this.y).val()) {
            toastr.error('Invalid input detected. Please refresh the page and try again.');
            return;
        }
        if (!$(this.password).val()) {
            toastr.error('Enter new password.');
            $(this.password).focus();
            return;
        } else if (!passwordRegex.test($(this.password).val())) {
            toastr.error('Password must have atleast one capital, one small, one number with minimum 8 characters.');
            $(this.password).focus();
            return;
        }
        if (!$(this.confrmPassword).val()) {
            toastr.error('Confirm new password.');
            $(this.confrmPassword).focus();
            return;
        } else if ($(this.password).val() !== $(this.confrmPassword).val()) {
            toastr.error('Passwords do not match.');
            $(this.confrmPassword).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
                $(this).delay(5000).queue(function () {
                    window.location.replace('./login.php');
                    return;
                });
                $(thisForm.x).val('');
                $(thisForm.y).val('');
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            grecaptcha.reset();
            submitBtn.prop('disabled', false);
            $(thisForm.password).val('');
            $(thisForm.confrmPassword).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $('#activationLinkForm').submit(function (e) {
        e.preventDefault();
        var submitBtn = $(this.submit);
        var thisForm = this;
        if (!$(this.formUsername).val()) {
            toastr.error('Enter username.');
            $(this.formUsername).focus();
            return;
        } else if (!alphaNumericRegex.test($(this.formUsername).val())) {
            toastr.error('Invalid characters found in username.');
            $(this.formUsername).focus();
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            dataType: 'json',
            cache: false,
            url: $(this).attr('action'),
            data: $(this).serialize()
        }).done(function (response) {
            if (response.error === 0) {
                toastr.success(response.responseText);
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            grecaptcha.reset();
            submitBtn.prop('disabled', false);
            $(thisForm.formUsername).val('');
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });
    $(document).on('submit', '#editPostForm', function (e) {
        e.preventDefault();
        var submitBtn = $("#submitEditPostForm");
        var thisForm = $("#editPostForm");
        if (!$("#postTitle").val()) {
            toastr.error('Enter post title.');
            $("#postTitle").focus();
            return;
        }
        if (!$("#postDescrp").val()) {
            toastr.error('Enter post description.');
            $("#postDescrp").focus();
            return;
        }
        if ($('#postCont').summernote('isEmpty')) {
            toastr.error("Content editor is empty");
            return;
        }
        submitBtn.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $(this).attr('action'),
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false
        }).done(function (response) {
            response = JSON.parse(response);
            if (response.error === 0) {
                $('#postCont').summernote('code', "");
                thisForm[0].reset();
                toastr.success(response.responseText);
                $(this).delay(500).queue(function () {
                    window.location.replace('./viewPost.php?id=' + response.code);
                    return;
                });
            } else if (response.error === 1) {
                toastr.error(response.responseText);
            } else if (response.error === 2) {
                toastr.error('Unauthorized request found. Please refresh and try again.');
            } else if (response.error === -1) {
                toastr.error('Error occurred while processing your request. Please try again after some time.');
            }
            submitBtn.prop('disabled', false);
            return;
        }).fail(function (jqXHR, exception) {
            submitBtn.prop('disabled', false);
            ajaxFailureResponse(jqXHR, exception);
        });
    });

    setInterval(function () {
        $.ajax({
            url: 'checkSessionTimeout.php',
            type: 'POST',
            success: function (response) {
                if (response == 1)
                    location.reload();
            }
        });
    }, 10000);

});

function disableEnableUser(thisBtn, operationText, cToken) {
    thisBtn = $(thisBtn);
    $.ajax({
        type: "POST",
        dataType: 'json',
        cache: false,
        url: "./phpHandler/disableEnableUserHandler.php",
        data: jQuery.param({
            memberId: thisBtn.attr('memberId'),
            operation: operationText,
            cToken: cToken
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
    }).done(function (response) {
        if (response.error === 0) {
            if (response.responseText == "disabled") {
                toastr.success("Member account disabled.");
                $('#disableUser' + thisBtn.attr('memberId')).hide();
                $('#enableUser' + thisBtn.attr('memberId')).show();
            } else if (response.responseText == "enabled") {
                toastr.success("Member account enabled.");
                $('#disableUser' + thisBtn.attr('memberId')).show();
                $('#enableUser' + thisBtn.attr('memberId')).hide();
            }
        } else if (response.error === 1) {
            toastr.error(response.responseText);
        } else if (response.error === 2) {
            toastr.error('Unauthorized request found. Please refresh and try again.');
        } else if (response.error === -1) {
            toastr.error('Error occurred while processing your request. Please try again after some time.');
        }
        return;
    }).fail(function (jqXHR, exception) {
        ajaxFailureResponse(jqXHR, exception);
    });
}

function approveRejectPostAjax(thisBtn, operationText, cToken) {
    thisBtn = $(thisBtn);
    thisBtn.prop('disabled', true);
    $.ajax({
        type: "POST",
        dataType: 'json',
        cache: false,
        url: "./phpHandler/approveRejectPostHandler.php",
        data: jQuery.param({
            postId: $('#postId').val(),
            operation: operationText,
            cToken: cToken
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
    }).done(function (response) {
        if (response.error === 0) {
            toastr.success(response.responseText);
            $(this).delay(500).queue(function () {
                $('#approveRejectDiv').hide(500).delay(500).html('');
            });
        } else if (response.error === 1) {
            toastr.error(response.responseText);
        } else if (response.error === 2) {
            toastr.error('Unauthorized request found. Please refresh and try again.');
        } else if (response.error === -1) {
            toastr.error('Error occurred while processing your request. Please try again after some time.');
        }
        thisBtn.prop('disabled', false);
        return;
    }).fail(function (jqXHR, exception) {
        thisBtn.prop('disabled', false);
        ajaxFailureResponse(jqXHR, exception);
    });
}

function getEditPostAjax(thisBtn, cToken) {
    thisBtn = $(thisBtn);
    thisBtn.prop('disabled', true);
    $.ajax({
        type: "POST",
        dataType: 'json',
        cache: false,
        url: "./phpHandler/getEditPostHandler.php",
        data: jQuery.param({
            postId: $('#postId').val(),
            cToken: cToken
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8'
    }).done(function (response) {
        if (response.error === 0) {
            $("#contentWrapper").delay(100).fadeOut(500).queue(function () {
                $(this).html(response.responseText);
                activateSummerNote();
                $(this).delay(100).show();
            });
        } else if (response.error === 1) {
            toastr.error(response.responseText);
        } else if (response.error === 2) {
            toastr.error('Unauthorized request found. Please refresh and try again.');
        } else if (response.error === -1) {
            toastr.error('Error occurred while processing your request. Please try again after some time.');
        }
        thisBtn.prop('disabled', false);
        return;
    }).fail(function (jqXHR, exception) {
        thisBtn.prop('disabled', false);
        ajaxFailureResponse(jqXHR, exception);
    });
}

function ajaxFailureResponse(jqXHR, exception) {
    var resp = "";
    if (jqXHR.status === 0) {
        resp = 'Network error.\n Check your connection.';
    } else if (jqXHR.status == 404) {
        resp = 'Requested page not found.';
    } else if (jqXHR.status == 500) {
        resp = 'Internal Server Error.';
    } else if (exception === 'parsererror') {
        resp = 'Requested JSON parse failed.';
    } else if (exception === 'timeout') {
        resp = 'Time out while processing your request.';
    } else if (exception === 'abort') {
        resp = 'Ajax request aborted.';
    } else {
        resp = 'Error occurred. Please try again.';
    }
    toastr.error(resp);
    return;
}

function validateFileUpload(fieldId, kb) {
    var file = document.getElementById(fieldId).files[0];
    if (file) {
        var t = file.type.split('/').pop().toLowerCase();
        if (t != "jpeg" && t != "jpg" && t != "png") {
            toastr.error('Please select a valid image file. Only JPG and PNG files are accepted.');
            $('#' + fieldId).val('');
            $('#' + fieldId + "Show").attr('src', '');
            return 0;
        }
        if (file.size > kb) {
            toastr.error('File upload error. Max upload size allowed is ' + kb / 1048576 + 'MB.');
            $('#' + fieldId).val('');
            $('#' + fieldId + "Show").attr('src', '');
            return 0;
        }
    }
    return 1;
}

function activateSummerNote() {
    $('#postCont').summernote({
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['hr']],
            ['view', ['fullscreen']],
        ],
        codeviewFilter: true,
        codeviewIframeFilter: true,
        callbacks: {
            onPaste: function (e) {
                var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                e.preventDefault();
                setTimeout(function () {
                    document.execCommand('insertText', false, bufferText);
                }, 10);
            }
        }
    });
}

function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("tableView");
    switching = true;
    dir = "asc";
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[n];
            y = rows[i + 1].getElementsByTagName("td")[n];
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}