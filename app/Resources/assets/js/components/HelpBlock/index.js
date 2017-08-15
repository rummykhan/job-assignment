import React from 'react';

const HelpBlock = (props) => (
    <span className={'help-block' + (!!props.isHidden ? 'hidden' : '')}>{props.children}</span>
);

export default HelpBlock;