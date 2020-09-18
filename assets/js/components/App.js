import React, { useState } from "react";
import Input from "./Input";
import Results from "./Results";

const App = () => {
  const [history, setHistory] = useState([]);

  const calculate = (expression) => {
    if (expression.trim().length === 0) {
      alert("Math expression can not be empty");
      return;
    }

    fetch("/calculate", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        expression,
      }),
    })
      .then((response) => Promise.all([response.ok, response.json()]))
      .then(([responseOk, body]) => {
        if (responseOk) {
          const mathResult = body.expression + " = " + body.result;
          appendResult(mathResult);
        } else {
          const { message = "Unknown error occurred" } = body;
          throw new Error(message);
        }
      })
      .catch((e) => {
        const mathResult = expression + " = ERROR " + e.message;
        appendResult(mathResult);
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
