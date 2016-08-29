window.jQuery(document).ready(function ($) {
	var wcccpp = wcccpp || {}
	
	function block ($el, flag) {
		if (flag === undefined) flag = true
		$el.attr('disabled', flag)
	}
	
	function _n (singular, plural, n) {
		if (n == 0 || n > 1) return plural
		return singular
	}
	
	wcccpp.calculator = {
	
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
		},
		
		cep_calculator_submit: function (evt) {
			evt.preventDefault()

			var $form = $(evt.currentTarget)
			var $formData = $form.serializeArray()

			var data = {
				'action': 'wcccpp_ajax',
				'sCepDestino': $formData[0].value 
			}
			
			console.log(data.sCepDestino)

			block($('.wcccpp-field .input-text'))
			block($('.wcccpp-field .button'))
			this.clearMessages()
			this.addMessage('Calculando... aguarde, por favor.')
			
			var self = this

			$.ajax({
				type: 'post',
				url: '/wp-admin/admin-ajax.php',
				data: data,
				success: function(res) {
					var NAMES = {
						'40010': 'SEDEX',
						'41106': 'PAC'
					}
					
					console.log(res)
					
					self.clearMessages()
					for (var i in res.data.cServico) {
						var service = res.data.cServico[i]
						var name = NAMES[service.Codigo] || 'Método Desconhecido'
						var cost = service.Valor
						var days = service.PrazoEntrega
						
						var msg = ''
						
						if (service.Erro != 0) {
							msg = typeof service.MsgErro === 'string' ? 
								service.MsgErro :
								'Ocorreu um erro no calculo do serviço ' + name + '. Por favor, tente mais tarde ou use a calculadora da página do carrinho.'
						} else {
							msg = name + ' (Entrega em ' + days + _n(' dia', ' dias', days) + '): R$' + cost
						}
						
						self.addMessage(msg)
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
	
	wcccpp.calculator.init()

})
