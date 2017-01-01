import React, { Component } from 'react';

class Results extends Component {
  render() {
	  const response = this.props.details;
	  let key = false;
	  if ( response.data ) {
	  	console.log( response.data );
		const themeInfo = response.data[ Object.keys( response.data )[0] ];

	    return (
	      <div className="results-container">
		  	<img src={themeInfo.url + 'screenshot.png'} alt={themeInfo.Name} />

			<div className="theme-info">
				<h4><strong>Theme Name:</strong> {themeInfo.Name}</h4>
				<h4><strong>Theme Site:</strong> <a href={themeInfo.ThemeURI} >{themeInfo.ThemeURI}</a></h4>
				<h4><strong>Theme Author:</strong> {themeInfo.Author}</h4>
				<h4><strong>Description:</strong> {themeInfo.Description}</h4>
			</div>
		  </div>
	    );
	} else if ( 'error' ===  response.status ) {
		return (
			<div className="results-container">
				<h2>{response.message}</h2>
			</div>
		)
	}
	return (
		<div></div>
	)
  }
}

export default Results;
