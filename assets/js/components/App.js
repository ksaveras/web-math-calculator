import React, { useState } from "react";
import Input from "./Input";
import Results from "./Results";
import axios from "axios";

const App = () => {
  const [history, setHistory] = useState([]);

  const calculate = (expression) => {
    if (expression.trim().length === 0) {
      alert("Math expression can not be empty");
      return;
    }

    axios
      .post("/calculate", { expression })
      .then((response) => {
        const { expression, result } = response.data;
        appendResult(`${expression} = ${result}`);
      })
      .catch((error) => {
        const { message = "Unknown error occurred" } = error.response.data;
        appendResult(
          `${expression} = ERROR (${error.response.status} ${error.response.statusText}) ${message}`
        );
      });
  };

  const appendResult = (mathResult) => {
    setHistory([...history, mathResult]);
  };

  return (
    <div className="card">
      <div className="card-body">
        <h3 className="card-title">Simple Math Calculator</h3>
        <Results answers={history} />
        <Input clickHandler={calculate} />
      </div>
    </div>
  );
};

export default App;
