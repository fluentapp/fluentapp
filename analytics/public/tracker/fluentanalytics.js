(function () {
    'use strict';

    var location = window.location;
    var document = window.document;

    var scriptDOM = document.getElementById('simplecharts-js');

    var apiURL = scriptDOM.getAttribute('data-api') || getApiUrl(scriptDOM);


    function sendEvent(event)
    {
        var data = {};

        data.event = event;
        data.page = location.href;
        data.referrer = document.referrer || null;
        data.domain = scriptDOM.getAttribute('data-domain');

        var request = new XMLHttpRequest();
        request.open('POST', apiURL, true);
        request.setRequestHeader('Content-Type', 'application/json');

        request.send(JSON.stringify(data));

        request.onreadystatechange = function () {
            if (request.readyState === 4) {
                if (request.status === 200) {
                    console.log(request.responseText);
                }


            }
        };
    }

    function getApiUrl(el)
    {
        return new URL(el.src).origin + '/event';
    }

    function log(message)
    {
        if (message)
        {
            console.log('Log: ' + message);
        }
    }



    sendEvent('pageview');


})();
