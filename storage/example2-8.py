metro_areas = [
    ('Tokyo','JP',36.933,(35.689722, 139.691667)),
    ('Delhi NCR', 'IN', 21.935,(28.613889, 77.20889)),
    ('Mexico City','MX', 20.142,(19.433333, -99.133333)),
    ('New York- Neward','US', 20.142,(40.808611, -74.020386)),
    ('Sao Pauol', 'BR', 19.649, (-23.54778, -46.635833)),
    ]
print('{:15} | {:9} | {9}'.format('', 'lat.', 'long.'))
fmt= '{:15} | {:9.4f} | {:9.4f}'
for name, cc, pop, (latitude, longitude) in metro_areas:
    if longitude <=0:
        print(fmt.format(name, latitude, longitude))
        
