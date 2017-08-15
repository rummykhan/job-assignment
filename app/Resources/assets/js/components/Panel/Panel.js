import React from 'react';

const Panel = (props) => (
    <div className={ `panel panel-${props.panel}` }>
        {props.children}
    </div>
);

export default Panel;
