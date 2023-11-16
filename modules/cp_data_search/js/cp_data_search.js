Drupal.behaviors.dataSearchBehavior = {
	attach: function(context) {
		const form = document.getElementById('js-data-search-form');
		form.querySelectorAll('.box-input').forEach((input) => {
			input.checked = false;
		});

		once('dataSearchBehavior', '.type .box-input', context).forEach((el) => {
			el.addEventListener('change', function () {
				Array.from(form.querySelectorAll('.type input')).filter((input) => input != el).forEach((el) => {
					el.checked = false;
				});

				const levels = getSelectedLevels()

				if (levels == '1,2') {
					form.querySelector('.themes').style.display = 'block';
				} else {
					form.querySelector('.themes').style.display = 'none';
					form.querySelectorAll('.theme input').forEach((input) => {
						input.checked = false;
					});
				}

				updateButtonLink(levels);
			});
		});

		once('dataSearchBehavior', '.theme .box-input', context).forEach((el) => {
			el.addEventListener('change', function () {
				const levels = getSelectedLevels()
				const themes = getSelectedThemes();

				updateButtonLink(levels, themes);
			});
		});

		function getSelectedLevels() {
			let checkedTypes = form.querySelector('input[name=type]:checked');
			return checkedTypes && checkedTypes.value;
		}

		function getSelectedThemes() {
			return Array.from(form.querySelectorAll('input[name=theme]:checked'), ({ value }) => value);
		}

		function updateButtonLink(levels, themes) {
			const levelQuery = typeof levels !== 'undefined' ? `"level":[${levels}]` : '';
			const themeQuery = levels == '1,2' && themes.length ? `,"theme":["${themes.join('","')}"]` : '';
			const project = levels == '1,2' ? `,"project":["icos"]` : '';

			const query = typeof levels !== 'undefined'
				? `#{"filterCategories":{${levelQuery}${themeQuery}${project}}}`
				: `#{"filterCategories":{"project":["icos"]}}`;

			form.querySelector('.btn-find-data').href = `https://data.icos-cp.eu/portal/${query}`;
		}

	}
};
