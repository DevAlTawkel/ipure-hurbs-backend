<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brandId = 1;

        $products = [

            // ── Weight Management ─────────────────────────────────────────────
            [
                'category' => 'weight-management',
                'name'  => 'SlimWell Protein Powder',
                'slug'  => 'slimwell-protein-powder',
                'sku'   => 'SLIM-PROT-500G',
                'price' => 85.14,
                'short_description' => 'Meal-replacement shake for healthy weight management and lean muscle tone.',
                'description' => "Benefits:\n• SlimWell Protein Powder is beneficial for helping in weight management.\n• It aids in boosting the immune system, enhancing the body's metabolism and helps in healthy weight loss practices.\n• It is a healthy meal replacement shake that helps to sustain and maintain lean muscle tone.\n• It contains Whey protein which aids in enhancing endurance and vitality, increases muscle protein synthesis and promotes lean tissue mass gain.\n• It helps in improving the metabolism of the body.\n• It helps in cleaning and detoxifying the body.\n\nHow to use:\nReplace one of the three meals of the day with SlimWell Shake every day. For best results, it is recommended to replace dinner with SlimWell Shake daily.\n\nStorage:\nThis product absorbs moisture quickly so do not keep container open, close immediately. Once opened consume product within 20 days.\n\nPackaging: 500 ml Bottle | Available size: 500 gm",
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'category' => 'weight-management',
                'name'  => 'SlimQem Capsules',
                'slug'  => 'slimqem-capsules',
                'sku'   => 'SLIMQEM-CAPS-60',
                'price' => 70.46,
                'short_description' => 'Natural capsules for weight management, metabolism boost and appetite suppression.',
                'description' => "Benefits:\n1. Weight Management: SlimWell is specifically crafted to help individuals achieve and maintain a healthy weight through meal replacement and appetite suppression.\n2. Boosts Metabolism: The blend of proteins and herbal extracts can help improve metabolic rate, facilitating more efficient calorie burning.\n3. Lean Muscle Maintenance: The whey protein content aids in muscle protein synthesis, helping users sustain lean muscle while losing fat.\n4. Detoxification: Ingredients like psyllium husk and certain herbal extracts support digestive health and detoxification.\n5. Immune Support: Vitamins, minerals, and Ayurvedic herbs work synergistically to bolster the immune system.\n6. Energy Enhancement: The formulation provides sustained energy levels.\n\nHow to Use:\nTake 2 capsules after meals or as directed by a healthcare provider.\n\nStorage:\nStore in a cool, dry place. After opening, consume within 20 days.\n\nKey Ingredients:\nWhey Protein Concentrate, Soy Protein Isolate, Fructose Powder, Psyllium Husk Powder, Ayurvedic Herbs (Vrikshamla/Garcinia Cambogia, Amalaki, Harada, Katuki).\n\nAvailable size: 60 Tabs",
                'stock' => 100,
                'is_featured' => true,
            ],

            // ── Bone & Joints Wellness ────────────────────────────────────────
            [
                'category' => 'bone-joints-wellness',
                'name'  => 'OrthoQem Oil',
                'slug'  => 'orthoqem-oil',
                'sku'   => 'ORTHOQ-OIL',
                'price' => 62.52,
                'short_description' => 'Herbal pain relief oil with 20+ Ayurvedic ingredients for joint and muscle pain.',
                'description' => "Daily massages with this powerful herbaceous pain soother, enriched with 20+ Ayurvedic ingredients, offer effective support for pain relief. No hot or cold burning sensation or redness on the skin after application.\n\nWhy Choose OrthoQem Herbal Pain Relief Oil?\n• Effective Relief: Soothes joint and muscle pain with daily use.\n• Skin-Friendly: Safe and gentle, without causing irritation.\n• Aromatic Experience: Enjoy a soothing massage with a pleasant aroma.\n\nDISCLAIMER: For external use only. Avoid contact with eyes and open wounds. Discontinue use if irritation occurs.\n\nUSAGE: For chronic pain. Intended for long-term, persistent pain conditions.\n\nAvailable sizes: 100ml, 50ml",
                'stock' => 80,
                'is_featured' => false,
            ],
            [
                'category' => 'bone-joints-wellness',
                'name'  => 'OrthoQem Tablet',
                'slug'  => 'orthoqem-tablet',
                'sku'   => 'ORTHOQ-TAB-60',
                'price' => 70.69,
                'short_description' => 'Herbo-mineral tablets for arthritis, joint pain and musculoskeletal relief.',
                'description' => "BioQem OrthoQem Tablets is a specialized herbo-mineral combination designed for the effective management of arthritic and joint pain. Its unique formula targets the swelling of joints and muscles, providing quick and long-lasting relief.\n\nBenefits:\n• Effective for spondylitis, muscular pains, backaches, and sprains\n• Reduces swelling of joints and muscles\n• Provides faster and more efficient pain relief\n• Strengthens joints and muscles\n\nComposition (per tablet):\n• Mahayograj guggul churna: 150 mg\n• Maharasnadi kwata churna: 125 mg\n• Nirgundi (Vitex nigundo) churna: 125 mg\n• Yavani (Trachyspermum ammi): 65 mg\n• Shankha bhasma: 65 mg\n• Godanthi bhasma: 65 mg\n\nRecommended Use: As advised by a healthcare professional. For best results, consume for a minimum of three months.\n\nAvailable size: 60 Tabs",
                'stock' => 60,
                'is_featured' => false,
            ],
            [
                'category' => 'bone-joints-wellness',
                'name'  => 'OrthoQem Gel',
                'slug'  => 'orthoqem-gel',
                'sku'   => 'ORTHOQ-GEL-30G',
                'price' => 62.70,
                'short_description' => 'Fast-acting herbal gel for musculoskeletal pain, non-greasy and skin-friendly.',
                'description' => "OrthoQem Gel is a powerful herbal formula designed to provide quick and effective relief from musculoskeletal pain.\n\nBenefits:\n• Herbal Pain Relief: Infused with potent Ayurvedic ingredients that target pain and inflammation.\n• Fast-Acting Formula: Provides immediate relief, quickly absorbing into the skin.\n• Non-Greasy Texture: Lightweight gel that leaves no residue.\n• Soothing Sensation: Delivers relief without causing burning or irritation.\n• Pleasant Aroma: Free from strong, overpowering odors.\n\nHow It Works:\n• Targets Pain at the Source: Herbal ingredients penetrate deeply to reduce inflammation.\n• Improves Mobility: Eases stiffness and promotes better joint function.\n• Quick Absorption: The gel format allows for rapid absorption.\n\nUsage:\n1. Squeeze a small amount onto your palm.\n2. Gently apply and massage onto the affected area until fully absorbed.\n3. Use 2-3 times daily or as directed by a healthcare professional.\n\nAvailable size: 30gm",
                'stock' => 70,
                'is_featured' => false,
            ],

            // ── Respiratory Care & Immunity Booster ───────────────────────────
            [
                'category' => 'respiratory-care-immunity-booster',
                'name'  => 'Breath Sure Ayurvedic Cough Kadha',
                'slug'  => 'breath-sure-ayurvedic-cough-kadha',
                'sku'   => 'BREATHSURE-KADHA',
                'price' => 63.12,
                'short_description' => 'Ayurvedic cough syrup for asthma, bronchitis, COPD, allergies and respiratory wellness.',
                'description' => "Indications: Asthma, Cough (including smoker's cough), COPD, Bronchitis, Allergies and Sinusitis, Common Cold.\n\nKey Benefits:\n• Supports Healthy Respiratory Function\n• Soothing Relief: Non-drowsy, non-habit forming formulation\n• Natural Expectorant: Facilitates the clearance of mucus and phlegm\n• Bronchodilator Properties: Helps prevent bronchospasm\n• Immune Boosting: Enhances body's defenses against allergens\n• Antioxidant Effects\n\nKey Ingredients:\nAvaani (Thyme), Bimbi (Ivy), Vacha (Acorus Root), Camphor, Clove, Cardamom, Black Pepper, Ginger, Tulasi (Holy Basil), Licorice, and many more.\n\nDosage:\n• Adults: Mix 10-15 ml with 20 ml of lukewarm water, taken 45 minutes after breakfast and dinner daily.\n• Children: Mix 2.5-5 ml with 10 ml of lukewarm water.\n\nAvailable sizes: 100 ml, 200 ml",
                'stock' => 90,
                'is_featured' => true,
            ],

            // ── Kidney Care ───────────────────────────────────────────────────
            [
                'category' => 'kidney-care',
                'name'  => 'UroQem Syrup',
                'slug'  => 'uroqem-syrup',
                'sku'   => 'UROQEM-SYR-200ML',
                'price' => 64.17,
                'short_description' => 'Natural urinary alkalizer for UTI, burning micturition and kidney stone support.',
                'description' => "Benefits:\n• All kinds of Urinary tract infections\n• Burning micturition\n• Poor renal function\n• Helps in kidney stone\n• Activates complete Urinary system\n• Natural urinary alkalizer\n\nKey Ingredients:\nShatavari, Kemua, Ajmod, Sowa, Shallaki, Kar, Kashni, Kharbuja Beej, Jeera, Damiana, Dang, Saunf, Nirgundi, Mulethi, Jatamansi, Rashbhari, Rewandchini, Majid, Ashoka, Satar Farsi, Lodhra, Baheda, Gokharu, Gonda, Ashwagandha, Sugar.\n\nAvailable size: 200 ml",
                'stock' => 55,
                'is_featured' => false,
            ],

            // ── Liver Care & Detoxification ───────────────────────────────────
            [
                'category' => 'liver-care-detoxification',
                'name'  => 'LivoQem DS-32',
                'slug'  => 'livoqem-ds-32',
                'sku'   => 'LIVOQEM-DS32-60',
                'price' => 67.34,
                'short_description' => 'Hepatoprotective tablets for fatty liver, hepatitis, jaundice and liver detoxification.',
                'description' => "LivoQem DS is a natural, effective solution for supporting liver health, detoxification, and overall well-being.\n\nKey Benefits:\n1. Detoxification and Liver Repair\n2. Fatty Liver and Hepatitis Support\n3. Reduces Toxins\n4. Supports Appetite and Digestion\n5. Helps in Jaundice and Anemia\n6. Holistic Liver Health\n\nKey Ingredients (per tablet):\n• Bhumi Amla (Phylanthus niruri) – 100 mg\n• Bhringraj (Eclipta alba) – 75 mg\n• Kutki (Picrorhiza kurroa) – 75 mg\n• Giloy (Tinospora cordifolia) – 50 mg\n• Kalmegh (Andrographis paniculata) – 50 mg\n• Makoy (Solanum nigrum) – 50 mg\n• Punarnava (Boerhavia diffusa) – 50 mg\n• Arjuna (Terminalia arjuna) – 25 mg\n• Daru Haldi (Berberis aristata) – 25 mg\n\nDirections: Adults — 2 tablets morning and evening before meals. Children — ½ tablet twice daily.\n\nAvailable size: 60 Tablets",
                'stock' => 65,
                'is_featured' => false,
            ],
            [
                'category' => 'liver-care-detoxification',
                'name'  => 'Live Amrit DS Syrup',
                'slug'  => 'live-amrit-ds-syrup',
                'sku'   => 'LIVEAMRIT-DS-200ML',
                'price' => 63.88,
                'short_description' => 'Polyherbal liver tonic for detoxification, hepatitis, fatty liver and jaundice.',
                'description' => "Live-Amrit DS Syrup offers an all-encompassing solution for liver health by combining potent herbs that support detoxification, protect against damage, and accelerate the healing process.\n\nKey Benefits:\n1. Improves Digestion and Appetite\n2. Restores Liver Function: Normalizes liver function test parameters\n3. Liver Protection & Repair: Accelerates liver cell regeneration\n4. Protection from Alcohol-Induced Damage\n5. Supports Recovery During Convalescence\n\nUseful in: Acute Viral Hepatitis, Fatty Liver Disease (NAFLD), Jaundice, Liver Detoxification, Liver Dysfunction and Cirrhosis, Metabolic Disorders.\n\nKey Ingredients: Milk Thistle, Kalmegh, Kutki, Punarnava, Bhringraj, Bhuiamla, Amla, Giloy, Makoi, Rewand Chini, Harar, Daruhaldi, Kumari (Aloe vera), Sarfonka.\n\nHow to Use:\n• Children: ½ teaspoon, 2 times a day\n• Adults: 2-3 teaspoonfuls, 3-4 times a day\n\nAvailable size: 200 ml",
                'stock' => 75,
                'is_featured' => false,
            ],

            // ── Heart Care ────────────────────────────────────────────────────
            [
                'category' => 'heart-care',
                'name'  => 'CardioPro Syrup',
                'slug'  => 'cardiopro-syrup',
                'sku'   => 'CARDIOPRO-SYR-500ML',
                'price' => 71.51,
                'short_description' => '100% natural, sugar-free cardiac tonic for cholesterol, blood pressure and heart health.',
                'description' => "Key Ingredients: Hawthorn, Burans, Grape Seed Extract, Kesar, Arjuna, Amla, Harad, Sanay, Shatavari, Ashwagandha, Kaali Mirch, Jeera, Adrak, Pudina.\n\nKey Benefits:\n1. 100% Natural & Sugar-Free\n2. Cardiac & Cardiovascular Tonic\n3. Fights Bad Cholesterol: Reduces LDL cholesterol\n4. Anti-Inflammatory: Helps control inflammation in the arteries\n5. Prevents Free Radical Damage\n6. Supports Blood Circulation\n7. Prevents Congestive Heart Failure\n8. Helpful in: Hypertension, Dyslipidemia, Coronary Heart Disease, Peripheral Artery Blockage\n9. Promotes Anti-Aging\n10. Relieves Insomnia & Abdominal Gas\n\nDirections: Take 20-30 ml twice a day in a non-metallic container. Recommended treatment duration: 4-12 weeks.\n\nAvailable size: 500 ml",
                'stock' => 40,
                'is_featured' => true,
            ],

            // ── Diabetic Care ─────────────────────────────────────────────────
            [
                'category' => 'diabetic-care',
                'name'  => 'DiboWell Protein Powder',
                'slug'  => 'dibowell-protein-powder',
                'sku'   => 'DIBOWELL-PROT-200G',
                'price' => 69.41,
                'short_description' => 'Complete nutrition for diabetics — supports blood sugar management and muscle health.',
                'description' => "BioQem DiboWell Protein Powder is designed to provide complete nutrition while helping with blood sugar management.\n\nAnti-Diabetic Blend:\n• Banaba Extract, Guggle, Bitter Melon Extract, Licorice Extract, Cinnamon Extract, Gurmar (Gymnema Sylvestre), Bilberry Extract, Fenugreek\n\nNutritional Blend:\n• Whey Protein Concentrate, Vegetable Oil (Soya Oil), Maltodextrin, Dextrose, Dietary Fiber, DHA, Alpha Lipoic Acid, L-Carnitine, L-Taurine, Bovine Colostrum, Vitamin Premix, Mineral Mixture\n\nKey Benefits:\n1. Overall Body Health\n2. Liver and Kidney Support: Maintains healthy blood sugar levels\n3. Heart Health: Regulates blood pressure\n4. Stomach Health: Reduces cravings\n5. Bone and Retinal Health: Prevents muscle loss\n\nHow to Use: Add 10 gm (1 level scoop) to 50 ml of water, stir and consume immediately. Use within 1 month after opening.\n\nAvailable size: 200 Gm",
                'stock' => 45,
                'is_featured' => false,
            ],
            [
                'category' => 'diabetic-care',
                'name'  => 'DiboQem Tablet',
                'slug'  => 'diboqem-tablet',
                'sku'   => 'DIBOQEM-TAB-60',
                'price' => 75.09,
                'short_description' => 'Clinically proven herbal tablets to regulate glucose, improve insulin sensitivity and lower blood sugar.',
                'description' => "BioQem DiboQem Tablets are a natural formulation designed to support healthy glucose metabolism, enhance insulin sensitivity, and increase glycogen storage in muscles.\n\nKey Benefits:\n• Regulates Glucose Metabolism\n• Improves Insulin Sensitivity\n• Boosts Glycogen Storage\n• Balances Cholesterol\n• Stimulates Fat Burn\n• Protects Liver\n• Cardioprotective & Immunomodulatory\n• Rejuvenates Pancreatic B Cells\n• Reduces Insulin Intake\n• Lowers Sugar Levels\n\nKey Ingredients (per tablet):\n• Swarnapatri (Cassia angustifolia) – 150 mg\n• Madhunashini (Gymnema sylvestre) – 120 mg\n• Saptachakra (Salacia oblonga) – 120 mg\n• Jambu (Eugenia jambolana) – 80 mg\n• Karavallaka (Momordica charantia) – 80 mg\n• Haridra (Curcuma longa) – 80 mg\n\nHow to Use: Take 2 tablets twice a day after meals.\n\nAvailable size: 60 Tabs",
                'stock' => 60,
                'is_featured' => true,
            ],

            // ── Digestive Health Enhancer ─────────────────────────────────────
            [
                'category' => 'digestive-health-enhancer',
                'name'  => 'LaxoQem Powder',
                'slug'  => 'laxoqem-powder',
                'sku'   => 'LAXOQEM-PWD-75G',
                'price' => 63.77,
                'short_description' => 'Ayurvedic constipation powder that regulates bowel movement and improves digestion.',
                'description' => "LaxoQem Constipation Powder is used to treat constipation and indigestion. It regulates bowel movement and activates the secretion of digestive acids to improve digestion.\n\nBenefits:\n• Provides relief from constipation by softening the stool\n• Helps maintain a healthy digestive system\n• Helps deal with indigestion\n• Stimulates secretion of digestive juices to improve digestion\n• 100% natural formula\n\nIngredients: Isabgol, Sonamukhi, Harad, Amaltas, Mulethi, Saunf, Kala Namak\n\nHow to Use: Consume 1-2 teaspoons followed by a glass of lukewarm water before bedtime or as directed by the physician.\n\nAvailable size: 75 gm",
                'stock' => 85,
                'is_featured' => false,
            ],
            [
                'category' => 'digestive-health-enhancer',
                'name'  => 'Digo-Amrit Syrup',
                'slug'  => 'digo-amrit-syrup',
                'sku'   => 'DIGOAMRIT-SYR-200ML',
                'price' => 63.88,
                'short_description' => 'Natural digestive syrup for indigestion, acidity, gas, bloating, GERD and constipation.',
                'description' => "DIGO-AMRIT Syrup is an effective and natural solution to address common digestive complaints such as indigestion, acidity, gas, bloating, and constipation.\n\nKey Benefits:\n1. Activates Digestive System\n2. Neutralizes Gastric Acid\n3. Relieves Gas and Flatulence\n4. Alleviates Heartburn\n5. Beneficial for GERD\n6. Improves Digestive Enzyme Secretion\n7. Appetite Stimulation\n8. Relieves Digestive Spasms and Colic\n\nKey Ingredients: Papaya, Guava, Sennamakki, Triphala, Bael Fruit, Imli Fruit, Dalchini, Pudhina, Sonth (Ginger), Marich (Black Pepper), Pippali, Jeera, Haritaki, Chitrak, and more.\n\nHow to Use:\n• Children: ½ teaspoonful, 2 times a day\n• Adults: 2-3 teaspoonfuls, 3-4 times a day\n\nAvailable size: 200 ml",
                'stock' => 70,
                'is_featured' => false,
            ],

            // ── Women's Health Enhancer ───────────────────────────────────────
            [
                'category' => 'womens-health-enhancer',
                'name'  => 'GyNo-Cyst Tablets',
                'slug'  => 'gyno-cyst-tablets',
                'sku'   => 'GYNOCYST-TAB-30',
                'price' => 73.61,
                'short_description' => 'Non-hormonal dietary supplement for PCOS/PCOD management and hormonal balance.',
                'description' => "Bioqem Pharma's GyNo-Cyst Tablet is a non-hormonal dietary supplement specifically designed to manage PCOS (Polycystic Ovary Syndrome) and PCOD (Polycystic Ovarian Disease).\n\nBenefits:\n• Provides a natural solution for ovulation and implantation\n• Enhances the body's insulin response\n• Maintains hormonal balance\n• Stimulates glucose removal from the blood\n• Assists in balancing cholesterol levels\n• Supports female reproductive health\n\nKey Ingredients (per film-coated tablet):\n• Myo-inositol: 1000 mg\n• Fenumannan® (Fenugreek Extract, 60% Galactomannan): 100 mg\n• L-methylfolate Calcium: 100 mcg\n• Vitamin D3: 1000 IU\n\nDirection of Use: Take one capsule daily or as directed by your healthcare professional.\n\nAvailable size: 30 Tablets",
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'category' => 'womens-health-enhancer',
                'name'  => 'GynoSure Syrup',
                'slug'  => 'gynosure-syrup',
                'sku'   => 'GYNOSURE-SYR',
                'price' => 64.40,
                'short_description' => 'Aids in managing menstrual irregularities, supports ovulation, and is beneficial for infrequent or light menstrual periods.',
                'description' => "Bioqem Pharma's GynoSure Syrup is a polyherbal, non-hormonal Ayurvedic remedy designed to address various female health issues including menstrual irregularities, PCOS, heavy bleeding, uterine dysfunctions, amenorrhea, ovulation problems, and female infertility.\n\nBenefits:\n• Enhances the health of the endometrium\n• Normalizes menstrual blood flow\n• Increases endometrial thickness for implantation\n• Improves pregnancy outcomes\n• Supports treatment for oligomenorrhea, DUB, metrorrhagia\n• Provides supportive therapy alongside ART\n• Helps with dysmenorrhea, PCOS, leucorrhea\n\nDirection of Use: 15-20 ml with normal water twice daily, an hour after meals. Minimum treatment duration: 3 months or as directed by a physician.\n\nNote: Discontinue during menstruation. Resume once period has ended.\n\nAvailable sizes: 200 ml, 470 ml",
                'stock' => 55,
                'is_featured' => true,
            ],

            // ── Men's Health Enhancer ─────────────────────────────────────────
            [
                'category' => 'mens-health-enhancer',
                'name'  => 'Shilajit Gold Resin',
                'slug'  => 'shilajit-gold-resin',
                'sku'   => 'SHILAJIT-GOLD-RESIN',
                'price' => 95.62,
                'short_description' => '100% pure Himalayan Shilajit resin with Ashwagandha and Swarna Bhasma for strength, stamina and vitality.',
                'description' => "Health Benefits:\n• Boosts Immunity: Strengthens the body's immune system\n• Enhances Strength & Stamina: Improves physical endurance and boosts vitality\n• Slows Aging: Helps reduce oxidative stress\n• Reduces Stress: Helps the body adapt to stress\n• Boosts Libido: Enhances sexual health and libido\n• Strengthens Bones & Muscles\n\nKey Ingredients (per 1g serving):\n• Shilajit (Asphaltum) – 350 mg\n• Coffee Extract (Coffea arabica) – 150 mg\n• Ashwagandha Extract (Withania somnifera) – 150 mg\n• Gokshura Extract (Tribulus terrestris) – 100 mg\n• Haldi Extract (Curcuma longa) – 50 mg\n• Kali Musli Extract (Curculigo orchioides) – 50 mg\n• Essential Oil Elaichi (Elettaria cardamomum) – 10 mg\n• Swarna Bhasma – 0.50 mg\n\nAdditional Features:\n• High Fulvic Acid Content for increased nutrient absorption\n• Rich in 80+ Micronutrients\n• 100% Pure Himalayan Shilajit\n• Made Using Authentic Marana Process\n• Clinically Tested for Heavy Metals",
                'stock' => 30,
                'is_featured' => true,
            ],
        ];

        foreach ($products as $data) {
            $categorySlug = $data['category'];
            unset($data['category']);

            $category = Category::where('slug', $categorySlug)->first();

            Product::create(array_merge($data, [
                'category_id' => $category?->id,
                'brand_id'    => $brandId,
                'rating'      => 4.5,
                'is_active'   => true,
                'is_trending' => false,
                'is_featured' => $data['is_featured'] ?? false,
            ]));
        }
    }
}
