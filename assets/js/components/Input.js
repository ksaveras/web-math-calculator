import React from 'react';
import PropTypes from 'prop-types';

export default class Input extends React.Component {

  render() {
    return (
      <div className="form-row">
        <div className="form-group col-md-9">
          <input type="text" className="form-control form-control-lg" value={this.props.value}
                 onChange={this.props.changeHandler}/>
        </div>
        <div className="form-group col-md-3">
          <button className="btn btn-lg btn-block btn-primary" onClick={this.props.clickHandler}>=</button>
        </div>
      </div>
    );
  }
}

Input.propTypes = {
  value: PropTypes.string,
  changeHandler: PropTypes.func,
  clickHandler: PropTypes.func,
};
