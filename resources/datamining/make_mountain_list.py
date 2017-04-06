import json
import requests
from pyquery import PyQuery as pq
import redis

if __name__ == '__main__':
    data = json.load(open('./ranges.json', 'r'))
    red = redis.StrictRedis()

    res = []

    for mrange in data:
        for subrange in mrange['subranges']:
            for location in subrange['locations']:
                height = red.get('mntn:location:{}:height'.format(location['id'])).decode('utf8')

                if not height:
                    print('Getting height for {}'.format(location['id']))
                    r = requests.get('https://www.mountain-forecast.com/peaks/{}'.format(location['id']))
                    d = pq(r.text)

                    height = d('#descr .height').text()

                    red.set('mntn:location:{}:height'.format(location['id']), height)

                res.append({
                    'id': location['id'],
                    'name': location['name'],
                    'fqn': '{} > {} > {}'.format(mrange['name'], subrange['name'], location['name']),
                    'height': height,
                })

    json.dump(res, open('./mountains.json', 'w'))
