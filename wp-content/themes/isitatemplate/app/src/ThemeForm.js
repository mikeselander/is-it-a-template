import React, { Component } from 'react';

class ThemeForm extends Component {
	submitForm( event ) {
		event.preventDefault();

		if ( ! this.props.validateURL( this.urlField.value ) ) {
			this.props.setErrorMessage( 'That\'s not a URL! 🤓' );
			return;
		}

		if ( '' !== this.poohBearField.value ) {
			this.props.setErrorMessage( 'I see what you did there 👻 Nice try :P' );
			return;
		}

		this.props.fetchThemeInfo( this.urlField.value, this.urlForm );
	}

	render() {
		return (
			<form ref={(input) => this.urlForm = input} onSubmit={this.submitForm.bind(this)}>
				<input ref={(input) => this.urlField = input} type="text" placeholder="Enter URL here." defaultValue="http://" className="url-input" />
				<label for="pooh-bear-likes" className="pooh-bear-likes">For Official Use Only</label>
    			<input ref={(input) => this.poohBearField = input} name="pooh-bear-likes" type="text" className="pooh-bear-likes" defaultValue="" />
				<button>Am I a Template? 🤔</button>
			</form>
		);
	}
}

export default ThemeForm;
