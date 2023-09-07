(function () {
    'use strict';

    var location = window.location;
    var document = window.document;
    var scriptDOM = document.getElementById('simplecharts-js');
    var apiURL = scriptDOM.getAttribute('data-api') || getApiUrl(scriptDOM);


    // Function to check if the window title contains any of the specified strings
    function containsStringInWindowTitle(patternStrings) {
        var title = window.document.title;
        for (var i = 0; i < patternStrings.length; i++) {
            var patternString = patternStrings[i];
            var regex = new RegExp(patternString, 'i'); // 'i' flag for case-insensitivity
            if (regex.test(title)) {
                return true; // Match found
            }
        }
        return false; // No match found
    }
    function sendEvent(eventType) {
        var data = {
            event: eventType,
            page: location.href,
            referrer: document.referrer || null,
            domain: scriptDOM.getAttribute('data-domain')
        };

        function makeRequest(data) {
            var request = new XMLHttpRequest();
            request.open('POST', apiURL, true);
            request.responseType = 'json';
            request.setRequestHeader('Content-Type', 'application/json');
            request.send(JSON.stringify(data));

            request.onreadystatechange = function () {
                if (request.status === 201 && request.readyState === 4) {
                    var settings = request.response.settings;
                    if (settings.page_not_found_enabled === true
                            && eventType !== 'not_found')
                    {
                        var stringsToCheck = ['404', 'Page Not Found']; // this should come from the site settings
                        if (containsStringInWindowTitle(stringsToCheck))
                        {
                            sendEvent('not_found'); // Make another request with 'not_found' event type
                        }

                    }
                }
            };
        }

        makeRequest(data);
    }

    function getApiUrl(el) {
        return new URL(el.src).origin + '/event';
    }

    sendEvent('pageview');
})();
