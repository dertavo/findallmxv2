'use strict';

const e = React.createElement;

class LikeButton extends React.Component {
  constructor(props) {
    super(props);
    this.state = { 
        nombre:'',
        liked: false };
  }

  render() {
    if (this.state.liked) {
      return 'You liked this.';
    }

    return (
        <div>
          <input type="text" value={this.state.nombre} onChange={this.handleInputChange} />
        </div>
      );
  }
}

const domContainer = document.querySelector('#like_button_container');
const root = ReactDOM.createRoot(domContainer);
root.render(e(LikeButton));