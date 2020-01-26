import React from "react";
import Input from "./Input";
import Results from "./Results";

export default class App extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      history: [],
      expression: '',
    };

    this.onChange = this.onChange.bind(this);
    this.calculate = this.calculate.bind(this);
  }

  onChange(event) {
    this.setState({expression: event.target.value});
  }

  calculate() {
    if (this.state.expression.trim().length === 0) {
      alert('Math expression can not be empty');
      return;
    }

    fetch('/calculate', {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        expression: this.state.expression
      })
    })
      .then(response => Promise.all([response.ok, response.json()]))
      .then(([responseOk, body]) => {
        if (responseOk) {
          const mathResult = body.expression + ' = ' + body.result;
          this.appendResult(mathResult);
        } else {
          const {message = 'Unknown error occurred'} = body;
          throw new Error(message);
        }
      })
      .catch(e => {
        const mathResult = this.state.expression + ' = ERROR ' + e.message;
        this.appendResult(mathResult);
      });
  }

  appendResult(mathResult) {
    const history = [...this.state.history, mathResult];
    this.setState({history, expression: ''});
  }

  render() {
    return (
      <div className="card">
        <div className="card-body">
          <h3 className="card-title">Simple Math Calculator</h3>
          <Results answers={this.state.history}/>
          <Input value={this.state.expression} changeHandler={this.onChange} clickHandler={this.calculate}/>
        </div>
      </div>
    )
  }
}
