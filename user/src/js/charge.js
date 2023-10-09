var stripe = Stripe('...');
var elements = stripe.elements();
var button = $('#payment-form button');

var style = {
  	base: {
    	color: '#fff',
    	fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    	fontSmoothing: 'antialiased',
    	fontSize: '16px',
    	'::placeholder': {
      	color: '#888'
    	},
    	iconColor: '#fff'
  	},
  	invalid: {
    	color: '#e28282',
    	iconColor: '#e28282'
  	}
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

card.mount('#card-element');
card.on('change', function(e) {
	var displayError = document.getElementById('card-errors');
	button.attr('disabled', (e.empty && !e.complete || e.error) ? true : false);
	displayError.textContent = (e.error) ? e.error.message : '';
});

// Handle form submission.
$('#payment-form').on('submit', function(e) {
	var $form = $(this);
	e.preventDefault();
	Dialog({
		title: 'Pay Now?',
		text: 'Are you sure you want to proceed with the payment?',
		showCancelButton: true,
		confirmButtonText: 'Yes',
		cancelButtonText: 'No',
		allowOutsideClick: false,
		showLoaderOnConfirm: true,
		preConfirm: function() {
		stripe.createToken(card).then(function(result) {
			if (result.error)
				return showDialog(result.error.message, 'error');

			$.post({
				url: $form.attr('action'),
				data: {
					stripeToken: result.token.id
				}
			}).done(function(result) {
				var res = JSON.parse(result);
			
				if (res['status'] == 'error')
					return showDialog(res['message'], 'error');

				if (res['status'] == 'success')
					window.location = res['message'];
			}).fail(function() {
				showDialog('Could not proceed due to an anonymous error.', 'error');
			});
		});
		}
	}).then(function(result) {
		if (result.value) {
			processing('Processing, please wait...');
		}
	});
});