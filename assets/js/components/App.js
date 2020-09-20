import React, { useState } from "react";
import Input from "./Input";
import Results from "./Results";
import calculateApi from "../api/api";

const App = () => {
  const [history, setHistory] = useState([]);

  const calculate = (expression) => {
    if (expression.trim().length === 0) {
      alert("Math expression can not be empty");
      return;
    }

    calculateApi(expression).then((result) => {
      setHistory([...history, `${expression} = ${result}`]);
    });
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
