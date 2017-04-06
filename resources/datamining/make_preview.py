import redis
import json

if __name__ == '__main__':
    red = redis.StrictRedis()

    res = []

    for key in red.keys('mntn:location:*:height'):
        mntn = key.decode('utf8')

        mntn_id = mntn.split(':')[2]

        res.append({
            'id': mntn_id,
            'name': red.get('mntn:locations:{}:name'.format(mntn_id)).decode('utf8'),
            'fqn': red.get('mntn:locations:{}:name'.format(mntn_id)).decode('utf8'),
            'height': red.get('mntn:location:{}:height'.format(mntn_id)).decode('utf8'),
        })

    json.dump(res, open('./preview.json', 'w'))
