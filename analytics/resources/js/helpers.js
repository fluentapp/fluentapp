export function baseURL(baseUrl, domain = '') {
    return baseUrl + (domain === '' ? '' : domain + '/');
}
export function prepareQueryString(baseUrl, domain = '', route='', filterObj = {}, extraParam = {}) {
    let baseurl = baseURL(baseUrl, domain) + (route === '' ? '' : route);
    const params = new URLSearchParams();
    for (const key in filterObj) {
        if (filterObj.hasOwnProperty(key)) {
            params.append(key, filterObj[key]);
        }
    }
    for (const key in extraParam) {
        if (extraParam.hasOwnProperty(key)) {
            params.append(key, extraParam[key]);
        }
    }
    return baseurl + (params.toString() == '' ? '' : '?' + params.toString());
}
export function kFormatter(num) {
   return Math.abs(num) > 999 ? Math.sign(num) * ((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num) * Math.abs(num)
}
export function secondsToMinutesSeconds(seconds) {
   const minutes = Math.floor(seconds / 60);
   const remainingSeconds = seconds % 60;
   const minutesString = minutes > 0 ? minutes + 'm ' : '';
   const secondsString = remainingSeconds > 0 ? remainingSeconds + ' s' : '';
   return ( minutes > 0 || remainingSeconds > 0) ? minutesString + secondsString : '0 s';
}
export function numberToPercentage(value) {
    const percentage = (value * 100).toFixed(2);
    return percentage + '%';
}
export function checkValueInKeys(value, obj) {
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            if (key === value) {
                return true;
            }
        }
    }
    return false;
};
export function extractMainDomain(url) {
    let cleanedUrl = url.replace(/^(https?:\/\/)?(www\d?\.)?/i, '');
    let mainDomain = cleanedUrl.split('.')[0];
    return mainDomain.charAt(0).toUpperCase() + mainDomain.slice(1);
}
export function appendToFilters(filtersData,filterCategory, filterValue, override = false) {
    let newFilterData = [];
    let isFound = false;
    for (const item of filtersData) {
        if (item.key === filterCategory) {
            isFound = true;
            if (override) { // replace the old filter with the new one
                newFilterData.push({
                    ...item,
                    value: [filterValue] 
                });
            }
            else {
                newFilterData.push({
                    ...item,
                    value: [...item.value, filterValue],
                });
           }
        } else {
            newFilterData.push(item);
        }
    }
    if (!isFound) {
        newFilterData.push({
            key: filterCategory,
            value: [filterValue],
        });
    }
    return newFilterData;
}