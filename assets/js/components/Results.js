import React from "react";
import PropTypes from "prop-types";

const Results = (props) => {
  const answers = props.answers.join("\n");

  return (
    <div className="pb-2">
      <textarea
        className="form-control no-resize"
        readOnly={true}
        rows="10"
        value={answers}
      />
    </div>
  );
};

Results.propTypes = {
  answers: PropTypes.array,
};

export default Results;
