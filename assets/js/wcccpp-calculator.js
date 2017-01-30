window.jQuery(document).ready(function ($) {
	var debug = false
	
	var wcccppCalculator = {
	
		init: function () {
			this.cep_calculator_submit = this.cep_calculator_submit.bind(this)
			this.cep_calculator_show = this.cep_calculator_show.bind(this)
	
			$('form.wcccpp-correios-calculator').on(
				'submit',
				this.cep_calculator_submit
			)
			
			$('a.wcccpp-button').on(
				'click',
				this.cep_calculator_show
			)
			
			this.callbacks = []
		},
		
		cep_calculator_submit: function (evt) {
			evt.preventDefault()

			var $form = $(evt.currentTarget)
			var data = $form.serialize() + '&action=wcccpp_ajax'

			block($('.wcccpp-field .input-text'))
			block($('.wcccpp-field .button'))
			this.clearMessages()
			this.addMessage('Calculando... aguarde, por favor.', 'loading')
			
			var self = this

			$.ajax({
				type: 'post',
				url: wcccpp_ajax.url,
				data: data,
				success: function(res) {
					if (debug) console.log(res)
					var i = 0,
						funcs = self.callbacks,
						len = funcs && funcs.length;

					for(; i < len; i++) {
						funcs[i].call(null, res.data)
					}
				},
				complete: function () {
					block($('.wcccpp-field .input-text'), false)
					block($('.wcccpp-field .button'), false)
				},
				error: function () {
					self.clearMessages()
					self.addMessage('Ocorreu um erro. Por favor, tente mais tarde.', 'error')
				}
			})
					
			return false
		},
		
		cep_calculator_show: function (evt) {
			var target = $('.wcccpp-calculator-inside')
			if (target.hasClass('open')) return
			target.addClass('open')
			target.slideDown()
		},
		
		addMessage: function (msg, type) {
			var $parent = $('.wcccpp-messages')
			var $child = $('<div />')
			
			if (type === undefined) type = "notice"
			
			$child.addClass(type)
			$child.html(msg || 'empty message?')
			$child.appendTo($parent)
		},
		
		clearMessages: function () {
			$('.wcccpp-messages').empty()
		}
	}
	
	function block ($el, flag) {
		if (flag === undefined) flag = true
		$el.attr('disabled', flag)
	}
	
	function _n (singular, plural, n) {
		if (n == 0 || n > 1) return plural
		return singular
	}
	
	wcccppCalculator.init()
	
	wcccppCalculator.defaultCallback = function(data) {
		wcccppCalculator.clearMessages()
		
		for(var i = 0; i < data.length; i++) {
			var _data = data[i]
			var msg = '';
			if (_data.error) {
				msg = 'Erro ao calcular o "' + _data.title +'": ' + _data.msg
			} else {
				msg = _data.title + ' (Entrega em até ' + _data.days + ' dias): ' + _data.price_formatted
			}
			
			wcccppCalculator.addMessage(msg, _data.error ? 'error' : '');
		}
		
	}
	
	wcccppCalculator.callbacks.push(wcccppCalculator.defaultCallback)
	
	window.wcccppCalculator = wcccppCalculator
})
