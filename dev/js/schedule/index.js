import React from 'react';
import * as ReactDOM from 'react-dom/client';

import App from './App';

import css from '../../scss/schedule.scss';

const styles = document.createElement('style');
styles.innerHTML = css;
document.head.appendChild(styles);

const container = document.getElementById('programme-schedule-metabox');

if (container) {
    const root = ReactDOM.createRoot(container);
    root.render(<App />);
}