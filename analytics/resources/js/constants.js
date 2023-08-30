export const metrics = {
    unique_visitors: {
        name: 'Unique Visitors',
        type: 'number'
    },
    total_visits: {
        name: 'Total Visits',
        type: 'number'
    },
    total_pageviews: {
        name: 'Total Pageviews',
        type: 'number'
    },
    views_per_visit: {
        name: 'Views Per Visit',
        type: 'number'
    },
    bounce_rate: {
        name: 'Bounce Rate',
        type: 'percentage'
    },
    visit_duration: {
        name: 'Visit Duration',
        type: 'time'
    }
};

export const locations = {
    countries: {
        name: 'Countries',
        url: 'countries',
        field: 'countries',
    },
    regions: {
        name: 'Regions',
        url: 'regions',
        field: 'regions',
    },
    cities: {
        name: 'Cities',
        url: 'cities',
        field: 'cities',
    },
};
export const devices = {
    browsers: {
        name: 'Browsers',
        url: 'browsers',
        field: 'browsers',
    },
    os: {
        name: 'OS',
        url: 'operating-systems',
        field: 'os',
    },
    sizes: {
        name: 'Sizes',
        url: 'device-sizes',
        field: 'sizes',
    },
};
export const sources = {
    sources: {
        name: 'Top Sources',
        url: 'sources',
        field: 'referrer',
    },
};
export const pages = {
    page: {
        name: 'Top Pages',
        url: 'top-pages',
        field: 'page',
    },
    entry_pages: {
        name: 'Entry Pages',
        url: 'entry-pages',
        field: 'entry_pages',
    },
    exit_pages: {
        name: 'Exit Pages',
        url: 'exit-pages',
        field: 'exit_pages',
    },
};