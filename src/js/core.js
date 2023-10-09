$.fn.showError = function (msg, show) {
	var $parent = $(this);
  	
	if (show) {
    	return $("<div>", {
      		class: "form-alert",
    	}).html(msg).appendTo($parent);
	}
  	
	$parent.html("");
};

function elementExists(elem) {
	return $(elem).length > 0;
}

$.fn.setFormDisability = function (set, type, loaderHandler) {
	var $form = $(this);
  
  	$form.find(":input").attr("disabled", set);
  	$form.find(':input[type="submit"]').val(type);

	if (set) {
		return $("<div>", {
			class: "spinner",
		}).appendTo(loaderHandler);
	}

	loaderHandler.html("");
};

$.fn.toggleInputError = function (toggle, msg) {
	var $input = $(this);

	if (toggle) {
		if (!$input.hasClass("input-error")) {
			$input.addClass("input-error").siblings().html(msg);
		}
		return;
	}

	if ($input.hasClass("input-error")) {
		$input.removeClass("input-error").siblings().html("");
	}
};

$.urlParam = function (name) {
	var results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
		window.location.search
	);
	return results !== null ? results[1] || 0 : null;
};

$(document).ready(function () {
  	var $doc = $(this),
    	w = window;
  
	/** ---------------------------------------------------------------------
   	 * Login script
   	 * ---------------------------------------------------------------------
   	 */

	if (elementExists("#login-form")) {
		var $loginAlert = $("#login-alert"),
			loginInputs = "#login-form :input";

		$("#login-form").on("submit", function (e) {
			var $form = $(this),
				formData = new FormData();

			e.preventDefault();

			$loginAlert.showError(false);
			$form.setFormDisability(true, "please wait...", $loginAlert);

			formData.append("grt_user_login", JSON.stringify({
				user_email_username: $("#login-email-username").val(),
				user_password: $("#login-password").val()
			}));

			$.post({
				url: $form.attr("action"),
				data: formData,
				processData: false,
				contentType: false,
			}).done(function(response) {
				var res = JSON.parse(response);

				if (res["status"] === "error") {
					$form.setFormDisability(false, "login", $loginAlert);
					return $loginAlert.showError(res["message"], true);
				}

				if (res["status"] === "success") {
					var refSRC = $.urlParam("_refsrc"),
						refFor = $.urlParam("ref_for");

					w.location = refSRC !== null && refFor == "paid_membership" ? decodeURIComponent(refSRC) : w.location.origin;
				}
			}).fail(function() {
				$form.setFormDisability(false, "login", $loginAlert);
				$loginAlert.showError("Could not proceed due to an anonymous error.", true);
			});
		});

		// Enable form inputs only after valid data have been typed
		$doc.on("input", loginInputs, function () {
			var allEmpty = false;

			$(loginInputs).each(function () {
				if ($(this).val() == "") 
					allEmpty = true;
			});

			if ($loginAlert.html() !== "") 
				$loginAlert.showError(false);
				
			$("#login-button").attr("disabled", allEmpty);
		});
	}

	/** ---------------------------------------------------------------------
	 * Registration script
	 * ---------------------------------------------------------------------
	 */

	if (elementExists("#reg-form")) {
		var $regAlert = $("#reg-alert"),
			$confirmPassword = $("#reg-password-retype"),
			$regButton = $("#reg-button"),
			regInputs = "#reg-form :input";

		$("#reg-form").on("submit", function (e) {
			var $form = $(this),
			formData = new FormData();

			e.preventDefault();

			$regAlert.showError(false);
			$form.setFormDisability(true, "please wait...", $regAlert);

			formData.append("grt_user_register", JSON.stringify({
				user_firstname: $("#reg-firstname").val(),
				user_lastname: $("#reg-lastname").val(),
				user_email: $("#reg-email").val(),
				user_password: $("#reg-password").val(),
				user_username: $("#reg-username").val()
			}));

			$.post({
				url: $form.attr("action"),
				data: formData,
				processData: false,
				contentType: false,
			}).done(function (response) {
				var res = JSON.parse(response);

				if (res["status"] === "error") {
					$form.setFormDisability(false, "register", $regAlert);
					return $regAlert.showError(res["message"], true);
				}

				if (res["status"] === "success") {
					$regAlert.html(res["message"]);
					$form.find(":input").hide();
					$("#login-question").hide();
				}
			}).fail(function () {
				$form.setFormDisability(false, "register", $regAlert);
				$regAlert.showError("Could not proceed due to an anonymous error.", true);
			});
		});

		// Validating first name, last name and username
		$doc.on("input", "#reg-firstname,#reg-lastname,#reg-username", function () {
			var $input = $(this),
				value = $input.val(),
				msg = "",
				status = false,
				len = value.length,
				id = $input.attr("id");

			if (len >= 1 && len < 2) {
				msg = "Should not be less than 2 characters";
				status = true;
			}

			if (len > 24) {
				msg = "Should not be more than 24 characters";
				status = true;
			}

			if (id == "reg-firstname" || id == "reg-lastname") {
				if (!/^[a-zA-Z]*$/.test(value)) {
					msg = "Numbers, spaces or symbols are not allowed";
					status = true;
				}
			} else if (id == "reg-username") {
				if (!/^[a-zA-Z0-9_]*$/.test(value)) {
					msg = "Only numbers and underscores are allowed";
					status = true;
				}
			}
			$input.toggleInputError(status, msg);
		});

		// Validating email
		$doc.on("input", "#reg-email", function validateEmail() {
			var $input = $(this),
				value = $input.val();

			$input.toggleInputError(value.length > 0 && !/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value), "Invalid format");
		});

		// Checking password length
		$doc.on("input", "#reg-password", function () {
			var $input = $(this),
				value = $input.val(),
				len = value.length,
				status = false,
				msg = "",
				confirmValue = $confirmPassword.val();

			if (len >= 1 && len < 8) {
				status = true;
				msg = "Must contain at least 8 characters";
			}

			$input.toggleInputError(status, msg);
			
			$confirmPassword
				.attr("disabled", !(len >= 8))
				.toggleInputError(!status && len >= 1 && value !== confirmValue && confirmValue.length >= 1, "Password does not match");
		});

		// Matching password
		$doc.on("input", "#reg-password-retype", function () {
			var $input = $(this),
				value = $input.val(),
				len = value.length,
				status = false;

			if (len >= 1 && value !== $("#reg-password").val()) 
				status = true;

			$input.toggleInputError(status, "Password does not match");
		});

		/* Set button disablity status */
		$doc.on("input", regInputs, function () {
			var inputStatus = false;

			$(regInputs).each(function () {
				var $input = $(this);

				if ($input.hasClass("input-error") || $input.val() == "")
					inputStatus = true;
			});
			$regButton.attr("disabled", inputStatus);
			
			if ($regAlert.html() !== "") 
				$regAlert.showError(false);
		});
	}
});
