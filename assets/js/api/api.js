import axios from "axios";

const calculateApi = async (expression) => {
  try {
    const response = await axios.post("/calculate", { expression });
    const { result } = response.data;
    return result;
  } catch (error) {
    const { message = "Unknown error occurred" } = error.response.data;
    return `ERROR (${error.response.status} ${error.response.statusText}) ${message}`;
  }
};

export default calculateApi;
