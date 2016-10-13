
var oArgs = {menuaction: 'eventplanner.uivendor.index', organization_number: true};
var strURL = phpGWLink('index.php', oArgs, true);
JqueryPortico.autocompleteHelper(strURL, 'vendor_name', 'vendor_id', 'vendor_container', 'name');

$(document).ready(function ()
{
	$("#number_of_units").change(function ()
	{
		calculate_total_amount();
	});
	$("#charge_per_unit").change(function ()
	{
		calculate_total_amount();
	});

	calculate_total_amount();

	$.formUtils.addValidator({
		name: 'naming',
		validatorFunction: function (value, $el, config, languaje, $form)
		{
			var v = false;
			var firstname = $('#firstname').val();
			var lastname = $('#lastname').val();
			var company_name = $('#company_name').val();
			var department = $('#department').val();
			if ((firstname != "" && lastname != "") || (company_name != "" && department != ""))
			{
				v = true;
			}
			return v;
		},
		errorMessage: lang['Name or company is required'],
		errorMessageKey: ''
	});
});

function set_tab(tab)
{
	$("#active_tab").val(tab);
}

function calculate_total_amount()
{
	var total_amount = 0

	var number_of_units = $("#number_of_units").val();
	var charge_per_unit = $("#charge_per_unit").val();

	if(charge_per_unit && charge_per_unit)
	{
		total_amount = number_of_units * charge_per_unit;
	}
	$("#total_amount").val(total_amount);
}

