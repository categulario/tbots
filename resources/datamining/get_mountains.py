#!/usr/bin/env python3
import json
import requests
import logging
import redis
from pyquery import PyQuery as pq

def get_cached_locations(b_location_id):
    location_id = b_location_id.decode('utf8')

    return {
        'id' : red.get('mntn:locations:{}:value'.format(location_id)).decode('utf8'),
        'name': red.get('mntn:locations:{}:name'.format(location_id)).decode('utf8'),
    }

def get_locations(subrange_id):
    if red.llen('mntn:subrange:{}:locations'.format(subrange_id)):
        print('Using cached subrange {}'.format(subrange_id))

        return list(map(get_cached_locations, red.lrange('mntn:subrange:{}:locations'.format(subrange_id), 0, -1)))

    print('Requesting locations for {}'.format(subrange_id))

    r = requests.get('https://www.mountain-forecast.com/subranges/{}/locations.js'.format(subrange_id), headers = {
        'x-requested-with': 'XMLHttpRequest',
    })
    d = pq(r.text)

    options = d('#location_filename_part').children()

    def get_options(option):
        red.rpush('mntn:subrange:{}:locations'.format(subrange_id), option.attrib['value'])
        red.set('mntn:locations:{}:value'.format(option.attrib['value']), option.attrib['value'])
        red.set('mntn:locations:{}:name'.format(option.attrib['value']), option.text)

        return {
            'id' : option.attrib['value'],
            'name' : option.text,
        }

    return list(map(get_options, options))

def get_subranges(range_id, range_name):
    print('Requesting subranges for {}'.format(range_id))

    r = requests.get('https://www.mountain-forecast.com/mountain_ranges/{}/subranges.js'.format(range_id), headers = {
        'x-requested-with': 'XMLHttpRequest',
    })
    d = pq(r.text)

    options = d('#subrange_id').children()

    if len(options) == 0:
        def get_location_options(option):
            red.rpush('mntn:subrange:{}:locations'.format(range_id), option.attrib['value'])
            red.set('mntn:locations:{}:value'.format(option.attrib['value']), option.attrib['value'])
            red.set('mntn:locations:{}:name'.format(option.attrib['value']), option.text)

            return {
                'id' : option.attrib['value'],
                'name' : option.text,
            }

        options = d('#location_filename_part').children()

        return [{
            'id' : range_id,
            'name' : range_name,
            'locations': list(map(get_location_options, options)),
        }]

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

            subranges = get_subranges(range_id, range_name)

            range_data = {
                'id' : range_id,
                'name': range_name,
                'subranges': subranges,
            }

            ranges.append(range_data)

    return ranges


if __name__ == '__main__':
    red = redis.StrictRedis()

    json.dump(get_ranges(), open('ranges.json', 'w'), indent=2)

    # print(get_subranges('alborz', 'Alborz'))
    # print(get_locations('sierra-madre-oriental'))
