import json
import requests
import logging
from pyquery import PyQuery as pq

def get_locations(subrange_id):
    print('Requesting locations for {}'.format(subrange_id))

    r = requests.get('https://www.mountain-forecast.com/subranges/{}/locations.js'.format(subrange_id), headers = {
        'x-requested-with': 'XMLHttpRequest',
    })
    d = pq(r.text)

    options = d('#location_filename_part').children()

    def get_options(option):
        return {
            'id' : option.attrib['value'],
            'name' : option.text,
        }

    return list(map(get_options, options))

def get_subranges(range_id):
    print('Requesting subranges for {}'.format(range_id))

    r = requests.get('https://www.mountain-forecast.com/mountain_ranges/{}/subranges.js'.format(range_id), headers = {
        'x-requested-with': 'XMLHttpRequest',
    })
    d = pq(r.text)

    options = d('#subrange_id').children()

    def get_options(option):
        return {
            'id' : option.attrib['value'],
            'name' : option.text,
            'locations' : get_locations(option.attrib['value']),
        }

    return list(map(get_options, options))

def get_ranges():
    ranges = []

    with open('./ranges') as rangesfile:
        for line in rangesfile:
            range_id, range_name = line.strip().split('\t')

            subranges = get_subranges(range_id)

            range_data = {
                'id' : range_id,
                'name': range_name,
                'subranges': subranges,
            }

            ranges.append(range_data)

    return ranges


if __name__ == '__main__':
    # json.dump(get_ranges(), open('ranges.json', 'w'), indent=2)
    # json.dump(get_subranges('mexican-ranges'), open('ranges.json', 'w'), indent=2)
    # print(get_subranges('mexican-ranges'))
    print(get_locations('sierra-madre-oriental'))
