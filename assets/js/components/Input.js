import React, { useState } from "react";
import PropTypes from "prop-types";

const Input = (props) => {
  const [expression, setExpression] = useState("");
  const clickHandler = () => {
    props.clickHandler(expression);
    setExpression("");
  };

  return (
    <div className="form-row">
      <div className="form-group col-md-9">
        <input
          type="text"
          className="form-control form-control-lg"
          value={expression}
          onChange={(event) => setExpression(event.target.value)}
        />
      </div>
      <div className="form-group col-md-3">
        <button
          className="btn btn-lg btn-block btn-primary"
          onClick={clickHandler}
        >
          =
        </button>
      </div>
    </div>
  );
};

Input.propTypes = {
  clickHandler: PropTypes.func,
};

export default Input;
