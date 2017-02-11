/* global themeInfo */
import React, { Component } from 'react';
import ThemeForm from './ThemeForm';
import Results from './Results';

class App extends Component {
	constructor() {
		super();
		this.fetchThemeInfo = this.fetchThemeInfo.bind( this );
		this.validateURL = this.validateURL.bind( this );
		this.setErrorMessage = this.setErrorMessage.bind( this );

		this.state = {
			template: {}
		};
	}

	fetchThemeInfo( url, form ) {
		const qualifiedURL = themeInfo.siteURL + '/wp-json/template/v1/fetchtheme/?url=' + url;
		const template = {...this.state.template};
		const cur = this;

		// Reset the state to blank.
		cur.setState( { template: {} } );

		// Add an in-motion state to the container.
		form.classList.add( 'active' );

		var request = new Request( qualifiedURL, {
			method: 'get',
		});

		// Actually get our theme info.
		fetch( request ).then( function( response ) {
			return response.json();
		} ).then(function( response ) {
			cur.setState( { template: response } );
			form.classList.remove( 'active' );
		});

	}

	setErrorMessage( message ) {
		const template = {...this.state.template};
		this.setState( { template: {
			status: 'error',
			message: message
		} } )
	}

	validateURL( textval ) {
	    const urlregex = /^(https?|ftp):\/\/([a-zA-Z0-9.-]+(:[a-zA-Z0-9.&%$-]+)*@)*((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9][0-9]?)(\.(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3}|([a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(:[0-9]+)*(\/($|[a-zA-Z0-9.,?'\\+&%$#=~_-]+))*$/;
	    return urlregex.test( textval );
	}

	render() {
		const title = ( this.state.template.data ) ? this.state.template.data[ Object.keys( this.state.template.data )[0] ].templateText : 'Is It a Template?';
		return (
			<div className="app">
				<div className="app-header">
					<h1>{title}</h1>
				</div>
				<ThemeForm validateURL={this.validateURL} fetchThemeInfo={this.fetchThemeInfo} setErrorMessage={this.setErrorMessage} />
				<Results details={this.state.template} />
			</div>
		);
	}
}

export default App;
