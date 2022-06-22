import app from '../views/App'

export const titleCase = string => {
    string = string.replace(/_/g, " ");
    var sentence = string.toLowerCase().split(" ");
    for (var i = 0; i < sentence.length; i++) {
        sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
    }
    return sentence.join(" ");
}

export const isLoggedIn = () => {
    return localStorage.getItem('authToken') != undefined;
}