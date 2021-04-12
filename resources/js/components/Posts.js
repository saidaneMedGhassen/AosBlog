import React from 'react';
import ReactDOM from 'react-dom';

function Posts() {
    return (
        <div className="container mt-5">
        </div>
    );
}

export default Posts;

// DOM element
if (document.getElementById('posts')) {
    ReactDOM.render(<Posts />, document.getElementById('posts'));
}
