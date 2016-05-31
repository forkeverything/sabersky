<?php

use App\ProductSubcategory;
use Illuminate\Database\Seeder;

class ProductCategoriesTableSeeder extends Seeder
{

    protected $categories = [
        'Agriculture' => [
            'Agricultural Growing Media',
            'Agricultural Waste',
            'Animal Products',
            'Beans',
            'Cocoa Beans',
            'Coffee Beans',
            'Farm Machinery & Equipment',
            'Feed',
            'Fresh Seafood',
            'Fruit',
            'Grain',
            'Herbal Cigars & Cigarettes',
            'Mushrooms & Truffles',
            'Nuts & Kernels',
            'Organic Produce',
            'Ornamental Plants',
            'Other Agriculture Products',
            'Plant & Animal Oil',
            'Plant Seeds & Bulbs',
            'Timber Raw Materials',
            'Vanilla Beans',
            'Vegetables',
        ],
        'Food & Beverage' => [
            'Alcoholic Beverage',
            'Baby Food',
            'Baked Goods',
            'Bean Products',
            'Canned Food',
            'Coffee',
            'Confectionery',
            'Dairy',
            'Drinking Water',
            'Egg & Egg Products',
            'Food Ingredients',
            'Fruit Products',
            'Grain Products',
            'Honey Products',
            'Instant Food',
            'Meat & Poultry',
            'Other Food & Beverage',
            'Seafood',
            'Seasonings & Condiments',
            'Slimming Food',
            'Snack Food',
            'Soft Drinks',
            'Tea',
            'Vegetable Products',
        ],
        'Apparel' => [
            'Apparel Design Services',
            'Apparel Processing Services',
            'Apparel Stock',
            'Boy’s Clothing',
            'Children’s Clothing',
            'Coats',
            'Costumes',
            'Dresses',
            'Ethnic Clothing',
            'Garment Accessories',
            'Girls’ Clothing',
            'Hoodies & Sweatshirts',
            'Hosiery',
            'Infant & Toddlers Clothing',
            'Jackets',
            'Jeans',
            'Ladies’ Blouses & Tops',
            'Mannequins',
            'Maternity Clothing',
            'Men’s Clothing',
            'Men’s Shirts',
            'Organic Cotton Clothing',
            'Other Apparel',
            'Pants & Trousers',
            'Plus Size Clothing',
            'Sewing Supplies',
            'Shorts',
            'Skirts',
            'Sleepwear',
            'Sportswear',
            'Stage & Dance Wear',
            'Suits & Tuxedo',
            'Sweaters',
            'Tag Guns',
            'Tank Tops',
            'T-Shirts',
            'Underwear',
            'Uniforms',
            'Used Clothes',
            'Vests & Waistcoats',
            'Wedding Apparel & Accessories',
            'Women’s Clothing',
            'Workwear',
        ],
        'Textile & Leather Product' => [
            'Down & Feather',
            'Fabric',
            'Fiber',
            'Fur',
            'Grey Fabric',
            'Home Textile',
            'Leather',
            'Leather Product',
            'Other Textiles & Leather Products',
            'Textile Accessories',
            'Textile Processing',
            'Textile Stock',
            'Thread',
            'Yarn',
            '100% Cotton Fabric',
            '100% Polyester Fabric',
            'Bedding Set',
            'Towel',
            'Chair Cover',
            'Genuine Leather',
        ],
        'Fashion Accessories' => [
            'Belt Accessories',
            'Belts',
            'Fashion Accessories Design Services',
            'Fashion Accessories Processing Services',
            'Fashion Accessories Stock',
            'Gloves & Mittens',
            'Headwear',
            'Neckwear',
            'Scarf, Hat & Glove Sets',
            'Hats & Caps',
            'Scarves & Shawls',
            'Hair Accessories',
            'Genuine Leather Belts',
            'Leather Gloves & Mittens',
            'Ties & Accessories',
            'Belt Buckles',
            'PU Belts',
            'Belt Chains',
            'Metal Belts',
            'Suspenders',
        ],
        'Timepieces, Jewelry, Eyewear' => [
            'Eyewear',
            'Jewelry',
            'Watches',
            'Eyeglasses Frames',
            'Sunglasses',
            'Sports Eyewear',
            'Body Jewelry',
            'Bracelets & Bangles',
            'Brooches',
            'Cuff Links & Tie Clips',
            'Earrings',
            'Jewelry Boxes',
            'Jewelry Sets',
            'Jewelry Tools & Equipment',
            'Loose Beads',
            'Loose Gemstone',
            'Necklaces',
            'Pendants & Charms',
            'Rings',
            'Wristwatches',
        ],
        'Automobiles & Motorcycles' => [
            'Air Intakes',
            'ATV',
            'ATV Parts',
            'Auto Chassis Parts',
            'Auto Clutch',
            'Auto Electrical System',
            'Auto Electronics',
            'Auto Engine',
            'Auto Ignition System',
            'Auto Steering System',
            'Automobiles',
            'Axles',
            'Body Parts',
            'Brake System',
            'Car Care & Cleaning',
            'Cooling System',
            'Crank Mechanism',
            'Exhaust System',
            'Exterior Accessories',
            'Fuel System',
            'Interior Accessories',
            'Lubrication System',
            'Motorcycle Accessories',
            'Motorcycle Parts',
            'Motorcycles',
            'Other Auto Parts',
            'Suspension System',
            'Transmission',
            'Tricycles',
            'Universal Parts',
            'UTV',
            'Valve Train',
            'Vehicle Equipment',
            'Vehicle Tools',
        ],
        'Transportation' => [
            'Aircraft',
            'Aviation Accessories',
            'Aviation Parts',
            'Bicycle',
            'Bicycle Accessories',
            'Bicycle Parts',
            'Boats & Ships',
            'Bus',
            'Bus Accessories',
            'Bus Parts',
            'Container',
            'Electric Bicycle',
            'Electric Bicycle Part',
            'Emergency Vehicles',
            'Golf Carts',
            'Locomotive',
            'Marine Supplies',
            'Personal Watercraft',
            'Railway Supplies',
            'Snowmobile',
            'Special Transportation',
            'Trailers',
            'Train Carriage',
            'Train Parts',
            'Truck',
            'Truck Accessories',
            'Truck Parts',
        ],
        'Luggage, Bags & Cases' => [
            'Bag & Luggage Making Materials',
            'Bag Parts & Accessories',
            'Business Bags & Cases',
            'Digital Gear & Camera Bags',
            'Handbags & Messenger Bags',
            'Luggage & Travel Bags',
            'Luggage Cart',
            'Other Luggage, Bags & Cases',
            'Special Purpose Bags & Cases',
            'Sports & Leisure Bags',
            'Wallets & Holders',
            'Carry-on Luggage',
            'Luggage Sets',
            'Trolley Bags',
            'Briefcases',
            'Cosmetic Bags & Cases',
            'Shopping Bags',
            'Handbags',
            'Backpacks',
            'Wallets',
        ],
        'Shoes & Accessories' => [
            'Baby Shoes',
            'Boots',
            'Casual Shoes',
            'Children’s Shoes',
            'Clogs',
            'Dance Shoes',
            'Dress Shoes',
            'Genuine Leather Shoes',
            'Men’s Shoes',
            'Other Shoes',
            'Sandals',
            'Shoe Materials',
            'Shoe Parts & Accessories',
            'Shoe Repairing Equipment',
            'Shoes Design Services',
            'Shoes Processing Services',
            'Shoes Stock',
            'Slippers',
            'Special Purpose Shoes',
            'Sports Shoes',
            'Used Shoes',
            'Women’s Shoes',
        ],
        'Computer Hardware & Software' => [
            'All-In-One PC',
            'Barebone System',
            'Blank Media',
            'Computer Cables & Connectors',
            'Computer Cases & Towers',
            'Computer Cleaners',
            'Desktops',
            'Fans & Cooling',
            'Firewall & VPN',
            'Floppy Drives',
            'Graphics Cards',
            'Hard Drives',
            'HDD Enclosure',
            'Industrial Computer & Accessories',
            'Keyboard Covers',
            'KVM Switches',
            'Laptop Accessories',
            'Laptop Cooling Pads',
            'Laptops',
            'Memory',
            'Modems',
            'Monitors',
            'Motherboards',
            'Mouse & Keyboards',
            'Mouse Pads',
            'Netbooks & UMPC',
            'Network Cabinets',
            'Network Cards',
            'Network Hubs',
            'Network Switches',
            'Networking Storage',
            'Optical Drives',
            'Other Computer Accessories',
            'Other Computer Parts',
            'Other Computer Products',
            'Other Drive & Storage Devices',
            'Other Networking Devices',
            'PC Stations',
            'PDAs',
            'Power Supply Units',
            'Printers',
            'Processors',
            'Routers',
            'Scanners',
            'Servers',
            'Software',
            'Sound Cards',
            'Tablet PC',
            'Tablet PC Stands',
            'Tablet Stylus Pen',
            'USB Flash Drives',
            'USB Gadgets',
            'USB Hubs',
            'Used Computers & Accessories',
            'Webcams',
            'Wireless Networking',
            'Workstations',
        ],
        'Home Appliance' => [
            'Air Conditioning Appliances',
            'Cleaning Appliances',
            'Hand Dryers',
            'Home Appliance Parts',
            'Home Appliances Stocks',
            'Home Heaters',
            'Kitchen Appliances',
            'Laundry Appliances',
            'Other Home Appliances',
            'Refrigerators & Freezers',
            'Water Heaters',
            'Water Treatment Appliances',
            'Wet Towel Dispensers',
            'Air Conditioners',
            'Fans',
            'Vacuum Cleaners',
            'Solar Water Heaters',
            'Cooking Appliances',
            'Coffee Makers',
            'Blenders',
        ],
        'Consumer Electronic' => [
            'Accessories & Parts',
            'Camera, Photo & Accessories',
            'Electronic Publications',
            'Home Audio, Video & Accessories',
            'Mobile Phone & Accessories',
            'Other Consumer Electronics',
            'Portable Audio, Video & Accessories',
            'Video Game & Accessories',
            'Mobile Phones',
            'Earphone & Headphone',
            'Power Banks',
            'Digital Camera',
            'Radio & TV Accessories',
            'Speaker',
            'Television',
            'Cables',
            'Charger',
            'Digital Battery',
            'Digital Photo Frame',
            '3D Glasses',
        ],
        'Security & Protection' => [
            'Access Control Systems & Products',
            'Alarm',
            'CCTV Products',
            'Firefighting Supplies',
            'Key',
            'Lock Parts',
            'Locks',
            'Locksmith Supplies',
            'Other Security & Protection Products',
            'Police & Military Supplies',
            'Roadway Safety',
            'Safes',
            'Security Services',
            'Self Defense Supplies',
            'Water Safety Products',
            'Workplace Safety Supplies',
            'CCTV Camera',
            'Bullet Proof Vest',
            'Alcohol Tester',
            'Fire Alarm',
        ],
        'Electrical Equipment & Supplies' => [
            'Batteries',
            'Circuit Breakers',
            'Connectors & Terminals',
            'Contactors',
            'Electrical Plugs & Sockets',
            'Electronic & Instrument Enclosures',
            'Fuse Components',
            'Fuses',
            'Generators',
            'Other Electrical Equipment',
            'Power Accessories',
            'Power Distribution Equipment',
            'Power Supplies',
            'Professional Audio, Video & Lighting',
            'Relays',
            'Switches',
            'Transformers',
            'Wires, Cables & Cable Assemblies',
            'Wiring Accessories',
            'Solar Cells, Solar Panel',
        ],
        'Electronic Compnents & Supplies' => [
            'Active Components',
            'EL Products',
            'Electronic Accessories & Supplies',
            'Electronic Data Systems',
            'Electronic Signs',
            'Electronics Production Machinery',
            'Electronics Stocks',
            'Optoelectronic Displays',
            'Other Electronic Components',
            'Passive Components',
            'LCD Modules',
            'LED Displays',
            'PCB & PCBA',
            'Keypads & Keyboards',
            'Insulation Materials & Elements',
            'Integrated Circuits',
            'Diodes',
            'Transistors',
            'Capacitors',
            'Resistors',
        ],
        'Telecommunication' => [
            'Antennas for Communications',
            'Communication Equipment',
            'Telephones & Accessories',
            'Communication Cables',
            'Fiber Optic Equipment',
            'Fixed Wireless Terminals',
            'WiFi Finder',
            'Telephone Accessories',
            'Corded Telephones',
            'Cordless Telephones',
            'Wireless Networking Equipment',
            'Telephone Headsets',
            'VoIP Products',
            'Repeater',
            'PBX',
            'Telecom Parts',
            'Phone Cards',
            'Telephone Cords',
            'Answering Machines',
            'Caller ID Boxes',
        ],
        'Sports & Entertainment' => [
            'Amusement Park',
            'Artificial Grass & Sports Flooring',
            'Fitness & Body Building',
            'Gambling',
            'Golf',
            'Indoor Sports',
            'Musical Instruments',
            'Other Sports & Entertainment Products',
            'Outdoor Sports',
            'Sports Gloves',
            'Sports Safety',
            'Sports Souvenirs',
            'Team Sports',
            'Tennis',
            'Water Sports',
            'Winter Sports',
            'Camping & Hiking',
            'Scooters',
            'Gym Equipment',
            'Swimming & Diving',
        ],
        'Gifts & Crafts' => [
            'Antique Imitation Crafts',
            'Art & Collectible',
            'Artificial Crafts',
            'Arts & Crafts Stocks',
            'Bamboo Crafts',
            'Carving Crafts',
            'Clay Crafts',
            'Cross Stitch',
            'Crystal Crafts',
            'Embroidery Crafts',
            'Feng Shui Crafts',
            'Festive & Party Supplies',
            'Flags, Banners & Accessories',
            'Folk Crafts',
            'Gift Sets',
            'Glass Crafts',
            'Holiday Gifts',
            'Home Decoration',
            'Key Chains',
            'Knitting & Crocheting',
            'Lacquerware',
            'Lanyard',
            'Leather Crafts',
            'Metal Crafts',
            'Money Boxes',
            'Music Boxes',
            'Natural Crafts',
            'Nautical Crafts',
            'Other Gifts & Crafts',
            'Paper Crafts',
            'Plastic Crafts',
            'Pottery & Enamel',
            'Religious Crafts',
            'Resin Crafts',
            'Sculptures',
            'Semi-Precious Stone Crafts',
            'Souvenirs',
            'Stickers',
            'Stone Crafts',
            'Textile & Fabric Crafts',
            'Wedding Decorations & Gifts',
            'Wicker Crafts',
            'Wood Crafts',
        ],
        'Toys & Hobbies' => [
            'Action Figure',
            'Baby Toys',
            'Balloons',
            'Candy Toys',
            'Classic Toys',
            'Dolls',
            'Educational Toys',
            'Electronic Toys',
            'Glass Marbles',
            'Inflatable Toys',
            'Light-Up Toys',
            'Noise Maker',
            'Other Toys & Hobbies',
            'Outdoor Toys & Structures',
            'Plastic Toys',
            'Pretend Play & Preschool',
            'Solar Toys',
            'Toy Accessories',
            'Toy Animal',
            'Toy Guns',
            'Toy Parts',
            'Toy Robots',
            'Toy Vehicle',
            'Wind Up Toys',
            'Wooden Toys',
        ],
        'Health & Medical' => [
            'Animal Extract',
            'Plant Extracts',
            'Body Weight',
            'Health Care Supplement',
            'Health Care Supplies',
            'Crude Medicine',
            'Prepared Drugs In Pieces',
            'Traditional Patented Medicines',
            'Body Fluid-Processing & Circulation Devices',
            'Clinical Analytical Instruments',
            'Dental Equipment',
            'Emergency & Clinics Apparatuses',
            'Equipments of Traditional Chinese Medicine',
            'General Assay & Diagnostic Apparatuses',
            'Implants & Interventional Materials',
            'Medical Consumable',
            'Medical Cryogenic Equipments',
            'Medical Software',
            'Physical Therapy Equipments',
            'Radiology Equipment & Accessories',
            'Sterilization Equipments',
            'Surgical Instrument',
            'Ultrasonic, Optical, Electronic Equipment',
            'Ward Nursing Equipments',
            'Medicines',
            'Veterinary Instrument',
            'Veterinary Medicine',
        ],
        'Beauty & Personal Care' => [
            'Baby Care',
            'Bath Supplies',
            'Beauty Equipment',
            'Body Art',
            'Breast Care',
            'Feminine Hygiene',
            'Fragrance & Deodorant',
            'Hair Care',
            'Hair Extensions & Wigs',
            'Hair Salon Equipment',
            'Makeup',
            'Makeup Tools',
            'Men Care',
            'Nail Supplies',
            'Oral Hygiene',
            'Other Beauty & Personal Care Products',
            'Sanitary Paper',
            'Shaving & Hair Removal',
            'Skin Care',
            'Skin Care Tool',
            'Spa Supplies',
            'Weight Loss',
        ],
        'Construction & Real Estate' => [
            'Aluminum Composite Panels',
            'Balustrades & Handrails',
            'Bathroom',
            'Boards',
            'Building Glass',
            'Ceilings',
            'Corner Guards',
            'Countertops,Vanity Tops & Table Tops',
            'Curtain Walls & Accessories',
            'Decorative Films',
            'Door & Window Accessories',
            'Doors & Windows',
            'Earthwork Products',
            'Elevators & Elevator Parts',
            'Escalators & Escalator Parts',
            'Faucets, Mixers & Taps',
            'Fiberglass Wall Meshes',
            'Fireplaces,Stoves',
            'Fireproofing Materials',
            'Floor Heating Systems & Parts',
            'Flooring & Accessories',
            'Formwork',
            'Gates',
            'Heat Insulation Materials',
            'HVAC Systems & Parts',
            'Kitchen',
            'Ladders & Scaffoldings',
            'Landscaping Stone',
            'Masonry Materials',
            'Metal Building Materials',
            'Mosaics',
            'Mouldings',
            'Multifunctional Materials',
            'Other Construction & Real Estate',
            'Plastic Building Materials',
            'Quarry Stone & Slabs',
            'Real Estate',
            'Soundproofing Materials',
            'Stairs & Stair Parts',
            'Stone Carvings and Sculptures',
            'Sunrooms & Glass Houses',
            'Tiles & Accessories',
            'Timber',
            'Tombstones and Monuments',
            'Wallpapers/Wall Coating',
            'Waterproofing Materials',
        ],
        'Home & Garden' => [
            'Bakeware',
            'Barware',
            'Bathroom Products',
            'Cooking Tools',
            'Cookware',
            'Garden Supplies',
            'Home Decor',
            'Home Storage & Organization',
            'Household Chemicals',
            'Household Cleaning Tools & Accessories',
            'Household Sundries',
            'Kitchen Knives & Accessories',
            'Laundry Products',
            'Pet Products',
            'Tableware',
            'Dinnerware',
            'Drinkware',
            'Baby Supplies & Products',
            'Rain Gear',
            'Lighters & Smoking Accessories',
        ],
        'Lights & Lighting' => [
            'Emergency Lighting',
            'Holiday Lighting',
            'Indoor Lighting',
            'LED Lighting',
            'Lighting Accessories',
            'Lighting Bulbs & Tubes',
            'Other Lights & Lighting Products',
            'Outdoor Lighting',
            'Professional Lighting',
            'LED Residential Lighting',
            'LED Outdoor Lighting',
            'Chandeliers & Pendant Lights',
            'Ceiling Lights',
            'Crystal Lights',
            'Stage Lights',
            'Street Lights',
            'Energy Saving & Fluorescent',
            'LED Landscape Lamps',
            'LED Professional Lighting',
            'LED Encapsulation Series',
        ],
        'Furniture' => [
            'Antique Furniture',
            'Baby Furniture',
            'Bamboo Furniture',
            'Children Furniture',
            'Commercial Furniture',
            'Folding Furniture',
            'Furniture Accessories',
            'Furniture Hardware',
            'Furniture Parts',
            'Glass Furniture',
            'Home Furniture',
            'Inflatable Furniture',
            'Metal Furniture',
            'Other Furniture',
            'Outdoor Furniture',
            'Plastic Furniture',
            'Rattan / Wicker Furniture',
            'Wood Furniture',
            'Living Room Furniture',
            'Bedroom Furniture',
        ],
        'Machinery' => [
            'Agriculture Machinery & Equipment',
            'Apparel & Textile Machinery',
            'Building Material Machinery',
            'Chemical Machinery & Equipment',
            'Electronic Products Machinery',
            'Energy & Mineral Equipment',
            'Engineering & Construction Machinery',
            'Food & Beverage Machinery',
            'General Industrial Equipment',
            'Home Product Making Machinery',
            'Industry Laser Equipment',
            'Machine Tool Equipment',
            'Metal & Metallurgy Machinery',
            'Other Machinery & Industry Equipment',
            'Packaging Machine',
            'Paper Production Machinery',
            'Pharmaceutical Machinery',
            'Plastic & Rubber Machinery',
            'Printing Machine',
            'Refrigeration & Heat Exchange Equipment',
            'Used Machinery & Equipment',
            'Woodworking Machinery',
        ],
        'Industrial Parts & Fabrication Services' => [
            'Ball Valves',
            'Bearing Accessory',
            'Bearings',
            'Brass Valves',
            'Butterfly Valves',
            'Ceramic Valves',
            'Check Valves',
            'Custom Fabrication Services',
            'Diaphragm Valves',
            'Filter Supplies',
            'Flanges',
            'Gaskets',
            'Gate Valves',
            'General Mechanical Components Design Services',
            'General Mechanical Components Stock',
            'Industrial Brake',
            'Linear Motion',
            'Machine Tools Accessory',
            'Manual Valves',
            'Motor Parts',
            'Motors',
            'Moulds',
            'Needle Valves',
            'Other General Mechanical Components',
            'Other Mechanical Parts',
            'Pipe Fittings',
            'Pneumatic & Hydraulic',
            'Power Transmission',
            'Pumps & Parts',
            'Seals',
            'Shafts',
            'Solenoid Valves',
            'Used General Mechanical Components',
            'Vacuum Valves',
            'Valve Parts',
            'Valves',
            'Welding & Soldering Supplies',
            'Less',
        ],
        'Tools' => [
            'Construction Tools',
            'Garden Tools',
            'Hand Tools',
            'Lifting Tools',
            'Material Handling Tools',
            'Other Tools',
            'Power Tool Accessories',
            'Power Tools',
            'Tool Design Services',
            'Tool Parts',
            'Tool Processing Services',
            'Tool Sets',
            'Tool Stock',
            'Tools Packaging',
            'Used Tools',
            'Electric Drill',
            'Knife',
            'Hand Carts & Trolleys',
            'Lawn Mower',
            'Sander',
        ],

        'Hardware' => [
            'Abrasive Tools',
            'Abrasives',
            'Brackets',
            'Chains',
            'Clamps',
            'Fasteners',
            'Hardware Stock',
            'Hooks',
            'Mould Design & Processing Services',
            'Other Hardware',
            'Springs',
            'Used Hardware',
            'Bolts',
            'Screws',
            'Nuts',
            'Nails',
            'Anchors',
            'Rivets',
            'Washers',
            'Other Fasteners',
        ],
        'Measurement & Analysis Instruments' => [
            'Analyzers',
            'Counters',
            'Electrical Instruments',
            'Electronic Measuring Instruments',
            'Flow Measuring Instruments',
            'Instrument Parts & Accessories',
            'Lab Supplies',
            'Level Measuring Instruments',
            'Measuring & Analysing Instrument Design Services',
            'Measuring & Analysing Instrument Processing Services',
            'Measuring & Analysing Instrument Stocks',
            'Measuring & Gauging Tools',
            'Optical Instruments',
            'Other Measuring & Analysing Instruments',
            'Physical Measuring Instruments',
            'Pressure Measuring Instruments',
            'Temperature Instruments',
            'Testing Equipment',
            'Timers',
            'Used Measuring & Analysing Instruments',
            'Weighing Scales',
        ],
        'Minerals & Metallurgy' => [
            'Aluminum',
            'Asbestos Products',
            'Asbestos Sheets',
            'Barbed Wire',
            'Billets',
            'Carbon',
            'Carbon Fiber',
            'Cast & Forged',
            'Cemented Carbide',
            'Ceramic Fiber Products',
            'Ceramics',
            'Copper',
            'Copper Forged',
            'Fiberglass Products',
            'Glass',
            'Graphite Products',
            'Ingots',
            'Iron',
            'Lead',
            'Lime',
            'Magnetic Materials',
            'Metal Scrap',
            'Metal Slabs',
            'Mineral Wool',
            'Molybdenum',
            'Nickel',
            'Non-Metallic Mineral Deposit',
            'Ore',
            'Other Metals & Metal Products',
            'Other Non-Metallic Minerals & Products',
            'Pig Iron',
            'Quartz Products',
            'Rare Earth & Products',
            'Rare Earth Magnets',
            'Refractory',
            'Steel',
            'Titanium',
            'Tungsten',
            'Wire Mesh',
            'Zinc',
        ],
        'Chemicals' => [
            'Gas Disposal',
            'Noise Reduction Device',
            'Other Environmental Products',
            'Other Excess Inventory',
            'Recycling',
            'Sewer',
            'Waste Management',
            'Water Treatment',
            'Textile Waste',
            'Waste Paper',
            'Other Recycling Products',
        ],
        'Rubber & Plastics' => [
            'Plastic Processing Service',
            'Plastic Products',
            'Plastic Projects',
            'Plastic Raw Materials',
            'Plastic Stocks',
            'Recycled Plastic',
            'Recycled Rubber',
            'Rubber Processing Service',
            'Rubber Products',
            'Rubber Projects',
            'Rubber Raw Materials',
            'Rubber Stocks',
            'Plastic Cards',
            'PVC',
            'Plastic Tubes',
            'HDPE',
            'Rubber Hoses',
            'Plastic Sheets',
            'LDPE',
            'Agricultural Rubber',
        ],
        'Energy' => [
            'Biodiesel',
            'Biogas',
            'Charcoal',
            'Coal',
            'Coal Gas',
            'Coke Fuel',
            'Crude Oil',
            'Electricity Generation',
            'Petrochemical Products',
            'Solar Energy Products',
            'Industrial Fuel',
            'Natural Gas',
            'Other Energy Related Products',
            'Wood Pellets',
            'Solar Energy Systems',
            'Lubricant',
            'Diesel Fuel',
            'Solar Chargers',
            'Solar Collectors',
            'Bitumen',
        ],
        'Environment' => [
            'Additives',
            'Adhesives & Sealants',
            'Agrochemicals',
            'Basic Organic Chemicals',
            'Catalysts & Chemical Auxiliary Agents',
            'Chemical Reagent Products',
            'Chemical Waste',
            'Custom Chemical Services',
            'Daily Chemical Raw Materials',
            'Flavour & Fragrance',
            'Inorganic Chemicals',
            'Non-Explosive Demolition Agents',
            'Organic Intermediates',
            'Other Chemicals',
            'Paints & Coatings',
            'Pharmaceuticals',
            'Pigment & Dyestuff',
            'Polymer',
            'Food Additive Products',
            'Fertilizer',
        ],
        'Packaging & Printing' => [
            'Adhesive Tape',
            'Agricultural Packaging',
            'Aluminum Foil',
            'Apparel Packaging',
            'Blister Cards',
            'Bottles',
            'Cans',
            'Chemical Packaging',
            'Composite Packaging Materials',
            'Cosmetics Packaging',
            'Electronics Packaging',
            'Food Packaging',
            'Gift Packaging',
            'Handles',
            'Hot Stamping Foil',
            'Jars',
            'Lids, Bottle Caps, Closures',
            'Media Packaging',
            'Metallized Film',
            'Other Packaging Applications',
            'Other Packaging Materials',
            'Packaging Bags',
            'Packaging Boxes',
            'Packaging Labels',
            'Packaging Product Stocks',
            'Packaging Rope',
            'Packaging Trays',
            'Packaging Tubes',
            'Paper & Paperboard',
            'Paper Packaging',
            'Pharmaceutical Packaging',
            'Plastic Film',
            'Plastic Packaging',
            'Printing Materials',
            'Printing Services',
            'Protective Packaging',
            'Pulp',
            'Shrink Film',
            'Strapping',
            'Stretch Film',
            'Tobacco Packaging',
            'Transport Packaging',
        ],
        'Office & School Supplies' => [
            'Art Supplies',
            'Badge Holder & Accessories',
            'Board',
            'Board Eraser',
            'Book Cover',
            'Books',
            'Calculator',
            'Calendar',
            'Clipboard',
            'Correction Supplies',
            'Desk Organizer',
            'Drafting Supplies',
            'Easels',
            'Educational Supplies',
            'Filing Products',
            'Letter Pad / Paper',
            'Magazines',
            'Map',
            'Notebooks & Writing Pads',
            'Office Adhesives & Tapes',
            'Office Binding Supplies',
            'Office Cutting Supplies',
            'Office Equipment',
            'Office Paper',
            'Other Office & School Supplies',
            'Paper Envelopes',
            'Pencil Cases & Bags',
            'Pencil Sharpeners',
            'Printer Supplies',
            'Stamps',
            'Stationery Set',
            'Stencils',
            'Writing Accessories',
            'Writing Instruments',
            'Yellow Pages',
        ],
        'Service Equipment' => [
            'Advertising Equipment',
            'Cargo & Storage Equipment',
            'Commercial Laundry Equipment',
            'Financial Equipment',
            'Funeral Supplies',
            'Other Service Equipment',
            'Restaurant & Hotel Supplies',
            'Store & Supermarket Supplies',
            'Trade Show Equipment',
            'Vending Machines',
            'Wedding Supplies',
            'Display Racks',
            'Advertising Players',
            'Advertising Light Boxes',
            'Hotel Amenities',
            'POS Systems',
            'Supermarket Shelves',
            'Stacking Racks & Shelves',
            'Refrigeration Equipment',
            'Trade Show Tent',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_categories')->truncate();
        DB::table('product_subcategories')->truncate();
        foreach ($this->categories as $category => $subcategories) {
            $productCategory = \App\ProductCategory::create([
                'name' => strtolower(str_replace(' ', '_', str_replace(' & ', '_', $category))),
                'label' => $category
            ]);
            foreach ($subcategories as $subcategory) {
                ProductSubcategory::create([
                    'name' => strtolower(str_replace(' ', '_', str_replace(' & ', '_', $subcategory))),
                    'label' => $subcategory,
                    'product_category_id' => $productCategory->id
                ]);
            }
        }
    }
}
