const AUTH = "auth";
export const LOGIN = `${AUTH}/login`;
export const REGISTER = `${AUTH}/register`;
export const LOGOUT = `${AUTH}/logout`;
export const PROFILE = `user`;

export const SUBSCRIBERS = "subscribers";
export const GET_SUBSCRIBER = id => `${SUBSCRIBERS}/${id}`;
export const CREATE_SUBSCRIBER = `${SUBSCRIBERS}/create`;
export const UPDATE_SUBSCRIBER = id => `${SUBSCRIBERS}/${id}/update`;
export const DELETE_SUBSCRIBER = id => `${SUBSCRIBERS}/${id}/delete`;
export const CHANGE_STATE = `${SUBSCRIBERS}/change-state`
export const BULK_DELETE = `${SUBSCRIBERS}/bulk-delete`

export const FIELDS = "fields";
export const CREATE_FIELD = `${FIELDS}/create`;
export const UPDATE_FIELD = id => `${FIELDS}/${id}/update`;