import React, {Component} from 'react';
import PropTypes from 'prop-types';

const Label = (props) => (
    <label htmlFor={props.htmlFor}>{props.label}</label>
);

export default Label;