import axios, { AxiosResponse } from 'axios';

const API_URL = 'http://localhost:8000';
export async function getApi(path: string) {
    const response = await axios.get(API_URL + path);
    return parseResponse(response);
}
export async function postApi(path: string, postBody: any) {
    const response = await axios.post(API_URL + path, postBody);
    return parseResponse(response);
}

function parseResponse(res: AxiosResponse) {
    const response = res.data;
    if (response.status === 'success') {
        return response.data;
    } else {
        throw new Error(response.error_message || 'unknown error');
    }
}
