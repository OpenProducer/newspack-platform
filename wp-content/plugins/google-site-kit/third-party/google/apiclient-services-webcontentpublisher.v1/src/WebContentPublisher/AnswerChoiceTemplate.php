<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace Google\Site_Kit_Dependencies\Google\Service\WebContentPublisher;

class AnswerChoiceTemplate extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unspecified answer category.
     */
    public const CATEGORY_ANSWER_CATEGORY_UNSPECIFIED = 'ANSWER_CATEGORY_UNSPECIFIED';
    /**
     * Interest | Academic Interests.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS = 'INTEREST__ACADEMIC_INTERESTS';
    /**
     * Interest | Automotive.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE = 'INTEREST__AUTOMOTIVE';
    /**
     * Interest | Books and Literature.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE = 'INTEREST__BOOKS_AND_LITERATURE';
    /**
     * Interest | Business and Finance.
     */
    public const CATEGORY_INTEREST__BUSINESS_AND_FINANCE = 'INTEREST__BUSINESS_AND_FINANCE';
    /**
     * Interest | Careers.
     */
    public const CATEGORY_INTEREST__CAREERS = 'INTEREST__CAREERS';
    /**
     * Interest | Family and Relationships.
     */
    public const CATEGORY_INTEREST__FAMILY_AND_RELATIONSHIPS = 'INTEREST__FAMILY_AND_RELATIONSHIPS';
    /**
     * Interest | Fine Art.
     */
    public const CATEGORY_INTEREST__FINE_ART = 'INTEREST__FINE_ART';
    /**
     * Interest | Food & Drink.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK = 'INTEREST__FOOD_AND_DRINK';
    /**
     * Interest | Health and Medical Services.
     */
    public const CATEGORY_INTEREST__HEALTH_AND_MEDICAL_SERVICES = 'INTEREST__HEALTH_AND_MEDICAL_SERVICES';
    /**
     * Interest | Healthy Living.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING = 'INTEREST__HEALTHY_LIVING';
    /**
     * Interest | Hobbies & Interests.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS = 'INTEREST__HOBBIES_AND_INTERESTS';
    /**
     * Interest | Home & Garden.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN = 'INTEREST__HOME_AND_GARDEN';
    /**
     * Interest | Medical Health.
     */
    public const CATEGORY_INTEREST__MEDICAL_HEALTH = 'INTEREST__MEDICAL_HEALTH';
    /**
     * Interest | Movies.
     */
    public const CATEGORY_INTEREST__MOVIES = 'INTEREST__MOVIES';
    /**
     * Interest | Music and Audio.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO = 'INTEREST__MUSIC_AND_AUDIO';
    /**
     * Interest | News and Politics.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS = 'INTEREST__NEWS_AND_POLITICS';
    /**
     * Interest | Personal Finance.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE = 'INTEREST__PERSONAL_FINANCE';
    /**
     * Interest | Pets.
     */
    public const CATEGORY_INTEREST__PETS = 'INTEREST__PETS';
    /**
     * Interest | Pharmaceuticals, Conditions, and Symptoms.
     */
    public const CATEGORY_INTEREST__PHARMACEUTICALS_CONDITIONS_AND_SYMPTOMS = 'INTEREST__PHARMACEUTICALS_CONDITIONS_AND_SYMPTOMS';
    /**
     * Interest | Pop Culture.
     */
    public const CATEGORY_INTEREST__POP_CULTURE = 'INTEREST__POP_CULTURE';
    /**
     * Interest | Real Estate.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE = 'INTEREST__REAL_ESTATE';
    /**
     * Interest | Shopping.
     */
    public const CATEGORY_INTEREST__SHOPPING = 'INTEREST__SHOPPING';
    /**
     * Interest | Sports.
     */
    public const CATEGORY_INTEREST__SPORTS = 'INTEREST__SPORTS';
    /**
     * Interest | Style & Fashion.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION = 'INTEREST__STYLE_AND_FASHION';
    /**
     * Interest | Technology & Computing.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING = 'INTEREST__TECHNOLOGY_AND_COMPUTING';
    /**
     * Interest | Television.
     */
    public const CATEGORY_INTEREST__TELEVISION = 'INTEREST__TELEVISION';
    /**
     * Interest | Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL = 'INTEREST__TRAVEL';
    /**
     * Interest | Video Gaming.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING = 'INTEREST__VIDEO_GAMING';
    /**
     * Interest | Academic Interests | Arts and Humanities.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS__ARTS_AND_HUMANITIES = 'INTEREST__ACADEMIC_INTERESTS__ARTS_AND_HUMANITIES';
    /**
     * Interest | Academic Interests | Language Learning.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS__LANGUAGE_LEARNING = 'INTEREST__ACADEMIC_INTERESTS__LANGUAGE_LEARNING';
    /**
     * Interest | Academic Interests | Life Sciences.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS__LIFE_SCIENCES = 'INTEREST__ACADEMIC_INTERESTS__LIFE_SCIENCES';
    /**
     * Interest | Academic Interests | Physical Science and Engineering.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS__PHYSICAL_SCIENCE_AND_ENGINEERING = 'INTEREST__ACADEMIC_INTERESTS__PHYSICAL_SCIENCE_AND_ENGINEERING';
    /**
     * Interest | Academic Interests | Social Sciences.
     */
    public const CATEGORY_INTEREST__ACADEMIC_INTERESTS__SOCIAL_SCIENCES = 'INTEREST__ACADEMIC_INTERESTS__SOCIAL_SCIENCES';
    /**
     * Interest | Automotive | Auto Buying and Selling.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__AUTO_BUYING_AND_SELLING = 'INTEREST__AUTOMOTIVE__AUTO_BUYING_AND_SELLING';
    /**
     * Interest | Automotive | Auto Shows.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__AUTO_SHOWS = 'INTEREST__AUTOMOTIVE__AUTO_SHOWS';
    /**
     * Interest | Automotive | Auto Technology.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__AUTO_TECHNOLOGY = 'INTEREST__AUTOMOTIVE__AUTO_TECHNOLOGY';
    /**
     * Interest | Automotive | Budget Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__BUDGET_CARS = 'INTEREST__AUTOMOTIVE__BUDGET_CARS';
    /**
     * Interest | Automotive | Car Culture.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__CAR_CULTURE = 'INTEREST__AUTOMOTIVE__CAR_CULTURE';
    /**
     * Interest | Automotive | Classic Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__CLASSIC_CARS = 'INTEREST__AUTOMOTIVE__CLASSIC_CARS';
    /**
     * Interest | Automotive | Concept Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__CONCEPT_CARS = 'INTEREST__AUTOMOTIVE__CONCEPT_CARS';
    /**
     * Interest | Automotive | Dash Cam Videos.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__DASH_CAM_VIDEOS = 'INTEREST__AUTOMOTIVE__DASH_CAM_VIDEOS';
    /**
     * Interest | Automotive | Driverless Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__DRIVERLESS_CARS = 'INTEREST__AUTOMOTIVE__DRIVERLESS_CARS';
    /**
     * Interest | Automotive | Green Vehicles.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__GREEN_VEHICLES = 'INTEREST__AUTOMOTIVE__GREEN_VEHICLES';
    /**
     * Interest | Automotive | Luxury Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__LUXURY_CARS = 'INTEREST__AUTOMOTIVE__LUXURY_CARS';
    /**
     * Interest | Automotive | Motorcycles.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__MOTORCYCLES = 'INTEREST__AUTOMOTIVE__MOTORCYCLES';
    /**
     * Interest | Automotive | Performance Cars.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__PERFORMANCE_CARS = 'INTEREST__AUTOMOTIVE__PERFORMANCE_CARS';
    /**
     * Interest | Automotive | Scooters.
     */
    public const CATEGORY_INTEREST__AUTOMOTIVE__SCOOTERS = 'INTEREST__AUTOMOTIVE__SCOOTERS';
    /**
     * Interest | Books and Literature | Art and Photography Books.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__ART_AND_PHOTOGRAPHY_BOOKS = 'INTEREST__BOOKS_AND_LITERATURE__ART_AND_PHOTOGRAPHY_BOOKS';
    /**
     * Interest | Books and Literature | Biographies.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__BIOGRAPHIES = 'INTEREST__BOOKS_AND_LITERATURE__BIOGRAPHIES';
    /**
     * Interest | Books and Literature | Children's Literature.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__CHILDRENS_LITERATURE = 'INTEREST__BOOKS_AND_LITERATURE__CHILDRENS_LITERATURE';
    /**
     * Interest | Books and Literature | Comics and Graphic Novels.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__COMICS_AND_GRAPHIC_NOVELS = 'INTEREST__BOOKS_AND_LITERATURE__COMICS_AND_GRAPHIC_NOVELS';
    /**
     * Interest | Books and Literature | Cookbooks.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__COOKBOOKS = 'INTEREST__BOOKS_AND_LITERATURE__COOKBOOKS';
    /**
     * Interest | Books and Literature | Fiction.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__FICTION = 'INTEREST__BOOKS_AND_LITERATURE__FICTION';
    /**
     * Interest | Books and Literature | Poetry.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__POETRY = 'INTEREST__BOOKS_AND_LITERATURE__POETRY';
    /**
     * Interest | Books and Literature | Travel Books.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__TRAVEL_BOOKS = 'INTEREST__BOOKS_AND_LITERATURE__TRAVEL_BOOKS';
    /**
     * Interest | Books and Literature | Young Adult Literature.
     */
    public const CATEGORY_INTEREST__BOOKS_AND_LITERATURE__YOUNG_ADULT_LITERATURE = 'INTEREST__BOOKS_AND_LITERATURE__YOUNG_ADULT_LITERATURE';
    /**
     * Interest | Business and Finance | Business.
     */
    public const CATEGORY_INTEREST__BUSINESS_AND_FINANCE__BUSINESS = 'INTEREST__BUSINESS_AND_FINANCE__BUSINESS';
    /**
     * Interest | Business and Finance | Economy.
     */
    public const CATEGORY_INTEREST__BUSINESS_AND_FINANCE__ECONOMY = 'INTEREST__BUSINESS_AND_FINANCE__ECONOMY';
    /**
     * Interest | Business and Finance | Industries.
     */
    public const CATEGORY_INTEREST__BUSINESS_AND_FINANCE__INDUSTRIES = 'INTEREST__BUSINESS_AND_FINANCE__INDUSTRIES';
    /**
     * Interest | Careers | Apprenticeships.
     */
    public const CATEGORY_INTEREST__CAREERS__APPRENTICESHIPS = 'INTEREST__CAREERS__APPRENTICESHIPS';
    /**
     * Interest | Careers | Career Advice.
     */
    public const CATEGORY_INTEREST__CAREERS__CAREER_ADVICE = 'INTEREST__CAREERS__CAREER_ADVICE';
    /**
     * Interest | Careers | Career Planning.
     */
    public const CATEGORY_INTEREST__CAREERS__CAREER_PLANNING = 'INTEREST__CAREERS__CAREER_PLANNING';
    /**
     * Interest | Careers | Remote Working.
     */
    public const CATEGORY_INTEREST__CAREERS__REMOTE_WORKING = 'INTEREST__CAREERS__REMOTE_WORKING';
    /**
     * Interest | Careers | Vocational Training.
     */
    public const CATEGORY_INTEREST__CAREERS__VOCATIONAL_TRAINING = 'INTEREST__CAREERS__VOCATIONAL_TRAINING';
    /**
     * Interest | Education | Adult Education.
     */
    public const CATEGORY_INTEREST__EDUCATION__ADULT_EDUCATION = 'INTEREST__EDUCATION__ADULT_EDUCATION';
    /**
     * Interest | Education | Online Education.
     */
    public const CATEGORY_INTEREST__EDUCATION__ONLINE_EDUCATION = 'INTEREST__EDUCATION__ONLINE_EDUCATION';
    /**
     * Interest | Family and Relationships | Parenting.
     */
    public const CATEGORY_INTEREST__FAMILY_AND_RELATIONSHIPS__PARENTING = 'INTEREST__FAMILY_AND_RELATIONSHIPS__PARENTING';
    /**
     * Interest | Fine Art | Costume.
     */
    public const CATEGORY_INTEREST__FINE_ART__COSTUME = 'INTEREST__FINE_ART__COSTUME';
    /**
     * Interest | Fine Art | Dance.
     */
    public const CATEGORY_INTEREST__FINE_ART__DANCE = 'INTEREST__FINE_ART__DANCE';
    /**
     * Interest | Fine Art | Design.
     */
    public const CATEGORY_INTEREST__FINE_ART__DESIGN = 'INTEREST__FINE_ART__DESIGN';
    /**
     * Interest | Fine Art | Digital Arts.
     */
    public const CATEGORY_INTEREST__FINE_ART__DIGITAL_ARTS = 'INTEREST__FINE_ART__DIGITAL_ARTS';
    /**
     * Interest | Fine Art | Fine Art Photography.
     */
    public const CATEGORY_INTEREST__FINE_ART__FINE_ART_PHOTOGRAPHY = 'INTEREST__FINE_ART__FINE_ART_PHOTOGRAPHY';
    /**
     * Interest | Fine Art | Modern Art.
     */
    public const CATEGORY_INTEREST__FINE_ART__MODERN_ART = 'INTEREST__FINE_ART__MODERN_ART';
    /**
     * Interest | Fine Art | Opera.
     */
    public const CATEGORY_INTEREST__FINE_ART__OPERA = 'INTEREST__FINE_ART__OPERA';
    /**
     * Interest | Fine Art | Theater.
     */
    public const CATEGORY_INTEREST__FINE_ART__THEATER = 'INTEREST__FINE_ART__THEATER';
    /**
     * Interest | Food & Drink | Alcoholic Beverages.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__ALCOHOLIC_BEVERAGES = 'INTEREST__FOOD_AND_DRINK__ALCOHOLIC_BEVERAGES';
    /**
     * Interest | Food & Drink | Barbecues and Grilling.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__BARBECUES_AND_GRILLING = 'INTEREST__FOOD_AND_DRINK__BARBECUES_AND_GRILLING';
    /**
     * Interest | Food & Drink | Cooking.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__COOKING = 'INTEREST__FOOD_AND_DRINK__COOKING';
    /**
     * Interest | Food & Drink | Desserts and Baking.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__DESSERTS_AND_BAKING = 'INTEREST__FOOD_AND_DRINK__DESSERTS_AND_BAKING';
    /**
     * Interest | Food & Drink | Dining Out.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__DINING_OUT = 'INTEREST__FOOD_AND_DRINK__DINING_OUT';
    /**
     * Interest | Food & Drink | Food Allergies.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__FOOD_ALLERGIES = 'INTEREST__FOOD_AND_DRINK__FOOD_ALLERGIES';
    /**
     * Interest | Food & Drink | Food Movements.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__FOOD_MOVEMENTS = 'INTEREST__FOOD_AND_DRINK__FOOD_MOVEMENTS';
    /**
     * Interest | Food & Drink | Healthy Cooking and Eating.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__HEALTHY_COOKING_AND_EATING = 'INTEREST__FOOD_AND_DRINK__HEALTHY_COOKING_AND_EATING';
    /**
     * Interest | Food & Drink | Non-Alcoholic Beverages.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__NON_ALCOHOLIC_BEVERAGES = 'INTEREST__FOOD_AND_DRINK__NON_ALCOHOLIC_BEVERAGES';
    /**
     * Interest | Food & Drink | Vegan Diets.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__VEGAN_DIETS = 'INTEREST__FOOD_AND_DRINK__VEGAN_DIETS';
    /**
     * Interest | Food & Drink | Vegetarian Diets.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__VEGETARIAN_DIETS = 'INTEREST__FOOD_AND_DRINK__VEGETARIAN_DIETS';
    /**
     * Interest | Food & Drink | World Cuisines.
     */
    public const CATEGORY_INTEREST__FOOD_AND_DRINK__WORLD_CUISINES = 'INTEREST__FOOD_AND_DRINK__WORLD_CUISINES';
    /**
     * Interest | Health and Medical Services | Health & Pharma.
     */
    public const CATEGORY_INTEREST__HEALTH_AND_MEDICAL_SERVICES__HEALTH_AND_PHARMA = 'INTEREST__HEALTH_AND_MEDICAL_SERVICES__HEALTH_AND_PHARMA';
    /**
     * Interest | Healthy Living | Children's Health.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__CHILDRENS_HEALTH = 'INTEREST__HEALTHY_LIVING__CHILDRENS_HEALTH';
    /**
     * Interest | Healthy Living | Fitness and Exercise.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__FITNESS_AND_EXERCISE = 'INTEREST__HEALTHY_LIVING__FITNESS_AND_EXERCISE';
    /**
     * Interest | Healthy Living | Men's Health.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__MENS_HEALTH = 'INTEREST__HEALTHY_LIVING__MENS_HEALTH';
    /**
     * Interest | Healthy Living | Nutrition.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__NUTRITION = 'INTEREST__HEALTHY_LIVING__NUTRITION';
    /**
     * Interest | Healthy Living | Senior Health.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__SENIOR_HEALTH = 'INTEREST__HEALTHY_LIVING__SENIOR_HEALTH';
    /**
     * Interest | Healthy Living | Weight Loss.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__WEIGHT_LOSS = 'INTEREST__HEALTHY_LIVING__WEIGHT_LOSS';
    /**
     * Interest | Healthy Living | Wellness.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__WELLNESS = 'INTEREST__HEALTHY_LIVING__WELLNESS';
    /**
     * Interest | Healthy Living | Women's Health.
     */
    public const CATEGORY_INTEREST__HEALTHY_LIVING__WOMENS_HEALTH = 'INTEREST__HEALTHY_LIVING__WOMENS_HEALTH';
    /**
     * Interest | Hobbies & Interests | Antiquing and Antiques.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__ANTIQUING_AND_ANTIQUES = 'INTEREST__HOBBIES_AND_INTERESTS__ANTIQUING_AND_ANTIQUES';
    /**
     * Interest | Hobbies & Interests | Arts and Crafts.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__ARTS_AND_CRAFTS = 'INTEREST__HOBBIES_AND_INTERESTS__ARTS_AND_CRAFTS';
    /**
     * Interest | Hobbies & Interests | Beekeeping.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__BEEKEEPING = 'INTEREST__HOBBIES_AND_INTERESTS__BEEKEEPING';
    /**
     * Interest | Hobbies & Interests | Birdwatching.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__BIRDWATCHING = 'INTEREST__HOBBIES_AND_INTERESTS__BIRDWATCHING';
    /**
     * Interest | Hobbies & Interests | Cigars.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__CIGARS = 'INTEREST__HOBBIES_AND_INTERESTS__CIGARS';
    /**
     * Interest | Hobbies & Interests | Collecting.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__COLLECTING = 'INTEREST__HOBBIES_AND_INTERESTS__COLLECTING';
    /**
     * Interest | Hobbies & Interests | Content Production.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__CONTENT_PRODUCTION = 'INTEREST__HOBBIES_AND_INTERESTS__CONTENT_PRODUCTION';
    /**
     * Interest | Hobbies & Interests | Games and Puzzles.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__GAMES_AND_PUZZLES = 'INTEREST__HOBBIES_AND_INTERESTS__GAMES_AND_PUZZLES';
    /**
     * Interest | Hobbies & Interests | Genealogy and Ancestry.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__GENEALOGY_AND_ANCESTRY = 'INTEREST__HOBBIES_AND_INTERESTS__GENEALOGY_AND_ANCESTRY';
    /**
     * Interest | Hobbies & Interests | Magic and Illusion.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__MAGIC_AND_ILLUSION = 'INTEREST__HOBBIES_AND_INTERESTS__MAGIC_AND_ILLUSION';
    /**
     * Interest | Hobbies & Interests | Model Toys.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__MODEL_TOYS = 'INTEREST__HOBBIES_AND_INTERESTS__MODEL_TOYS';
    /**
     * Interest | Hobbies & Interests | Musical Instruments.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__MUSICAL_INSTRUMENTS = 'INTEREST__HOBBIES_AND_INTERESTS__MUSICAL_INSTRUMENTS';
    /**
     * Interest | Hobbies & Interests | Paranormal Phenomena.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__PARANORMAL_PHENOMENA = 'INTEREST__HOBBIES_AND_INTERESTS__PARANORMAL_PHENOMENA';
    /**
     * Interest | Hobbies & Interests | Radio Control.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__RADIO_CONTROL = 'INTEREST__HOBBIES_AND_INTERESTS__RADIO_CONTROL';
    /**
     * Interest | Hobbies & Interests | Sci-fi and Fantasy.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__SCI_FI_AND_FANTASY = 'INTEREST__HOBBIES_AND_INTERESTS__SCI_FI_AND_FANTASY';
    /**
     * Interest | Hobbies & Interests | Workshops and Classes.
     */
    public const CATEGORY_INTEREST__HOBBIES_AND_INTERESTS__WORKSHOPS_AND_CLASSES = 'INTEREST__HOBBIES_AND_INTERESTS__WORKSHOPS_AND_CLASSES';
    /**
     * Interest | Home & Garden | Gardening.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__GARDENING = 'INTEREST__HOME_AND_GARDEN__GARDENING';
    /**
     * Interest | Home & Garden | Home Entertaining.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__HOME_ENTERTAINING = 'INTEREST__HOME_AND_GARDEN__HOME_ENTERTAINING';
    /**
     * Interest | Home & Garden | Home Improvement.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__HOME_IMPROVEMENT = 'INTEREST__HOME_AND_GARDEN__HOME_IMPROVEMENT';
    /**
     * Interest | Home & Garden | Interior Decorating.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__INTERIOR_DECORATING = 'INTEREST__HOME_AND_GARDEN__INTERIOR_DECORATING';
    /**
     * Interest | Home & Garden | Landscaping.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__LANDSCAPING = 'INTEREST__HOME_AND_GARDEN__LANDSCAPING';
    /**
     * Interest | Home & Garden | Outdoor Decorating.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__OUTDOOR_DECORATING = 'INTEREST__HOME_AND_GARDEN__OUTDOOR_DECORATING';
    /**
     * Interest | Home & Garden | Remodeling & Construction.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__REMODELING_AND_CONSTRUCTION = 'INTEREST__HOME_AND_GARDEN__REMODELING_AND_CONSTRUCTION';
    /**
     * Interest | Home & Garden | Smart Home.
     */
    public const CATEGORY_INTEREST__HOME_AND_GARDEN__SMART_HOME = 'INTEREST__HOME_AND_GARDEN__SMART_HOME';
    /**
     * Interest | Movies | Action and Adventure Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__ACTION_AND_ADVENTURE_MOVIES = 'INTEREST__MOVIES__ACTION_AND_ADVENTURE_MOVIES';
    /**
     * Interest | Movies | Animation Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__ANIMATION_MOVIES = 'INTEREST__MOVIES__ANIMATION_MOVIES';
    /**
     * Interest | Movies | Comedy Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__COMEDY_MOVIES = 'INTEREST__MOVIES__COMEDY_MOVIES';
    /**
     * Interest | Movies | Crime and Mystery Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__CRIME_AND_MYSTERY_MOVIES = 'INTEREST__MOVIES__CRIME_AND_MYSTERY_MOVIES';
    /**
     * Interest | Movies | Documentary Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__DOCUMENTARY_MOVIES = 'INTEREST__MOVIES__DOCUMENTARY_MOVIES';
    /**
     * Interest | Movies | Drama Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__DRAMA_MOVIES = 'INTEREST__MOVIES__DRAMA_MOVIES';
    /**
     * Interest | Movies | Family and Children Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__FAMILY_AND_CHILDREN_MOVIES = 'INTEREST__MOVIES__FAMILY_AND_CHILDREN_MOVIES';
    /**
     * Interest | Movies | Fantasy Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__FANTASY_MOVIES = 'INTEREST__MOVIES__FANTASY_MOVIES';
    /**
     * Interest | Movies | Horror Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__HORROR_MOVIES = 'INTEREST__MOVIES__HORROR_MOVIES';
    /**
     * Interest | Movies | Indie and Arthouse Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__INDIE_AND_ARTHOUSE_MOVIES = 'INTEREST__MOVIES__INDIE_AND_ARTHOUSE_MOVIES';
    /**
     * Interest | Movies | Romance Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__ROMANCE_MOVIES = 'INTEREST__MOVIES__ROMANCE_MOVIES';
    /**
     * Interest | Movies | Science Fiction Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__SCIENCE_FICTION_MOVIES = 'INTEREST__MOVIES__SCIENCE_FICTION_MOVIES';
    /**
     * Interest | Movies | World Movies.
     */
    public const CATEGORY_INTEREST__MOVIES__WORLD_MOVIES = 'INTEREST__MOVIES__WORLD_MOVIES';
    /**
     * Interest | Music and Audio | Adult Album Alternative.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__ADULT_ALBUM_ALTERNATIVE = 'INTEREST__MUSIC_AND_AUDIO__ADULT_ALBUM_ALTERNATIVE';
    /**
     * Interest | Music and Audio | Adult Contemporary Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__ADULT_CONTEMPORARY_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__ADULT_CONTEMPORARY_MUSIC';
    /**
     * Interest | Music and Audio | Alternative Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__ALTERNATIVE_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__ALTERNATIVE_MUSIC';
    /**
     * Interest | Music and Audio | Blues.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__BLUES = 'INTEREST__MUSIC_AND_AUDIO__BLUES';
    /**
     * Interest | Music and Audio | Children's Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__CHILDRENS_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__CHILDRENS_MUSIC';
    /**
     * Interest | Music and Audio | Classic Hits.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__CLASSIC_HITS = 'INTEREST__MUSIC_AND_AUDIO__CLASSIC_HITS';
    /**
     * Interest | Music and Audio | Classical Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__CLASSICAL_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__CLASSICAL_MUSIC';
    /**
     * Interest | Music and Audio | College Radio.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__COLLEGE_RADIO = 'INTEREST__MUSIC_AND_AUDIO__COLLEGE_RADIO';
    /**
     * Interest | Music and Audio | Comedy (Music and Audio).
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__COMEDY_MUSIC_AND_AUDIO = 'INTEREST__MUSIC_AND_AUDIO__COMEDY_MUSIC_AND_AUDIO';
    /**
     * Interest | Music and Audio | Contemporary Hits/Pop/Top 40.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__CONTEMPORARY_HITS_POP_TOP_40 = 'INTEREST__MUSIC_AND_AUDIO__CONTEMPORARY_HITS_POP_TOP_40';
    /**
     * Interest | Music and Audio | Country Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__COUNTRY_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__COUNTRY_MUSIC';
    /**
     * Interest | Music and Audio | Dance and Electronic Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__DANCE_AND_ELECTRONIC_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__DANCE_AND_ELECTRONIC_MUSIC';
    /**
     * Interest | Music and Audio | Gospel Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__GOSPEL_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__GOSPEL_MUSIC';
    /**
     * Interest | Music and Audio | Hip Hop Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__HIP_HOP_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__HIP_HOP_MUSIC';
    /**
     * Interest | Music and Audio | Inspirational/New Age Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__INSPIRATIONAL_NEW_AGE_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__INSPIRATIONAL_NEW_AGE_MUSIC';
    /**
     * Interest | Music and Audio | Jazz.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__JAZZ = 'INTEREST__MUSIC_AND_AUDIO__JAZZ';
    /**
     * Interest | Music and Audio | Oldies/Adult Standards.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__OLDIES_ADULT_STANDARDS = 'INTEREST__MUSIC_AND_AUDIO__OLDIES_ADULT_STANDARDS';
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__RNB_SOUL_FUNK = 'INTEREST__MUSIC_AND_AUDIO__RNB_SOUL_FUNK';
    /**
     * Interest | Music and Audio | Reggae.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__REGGAE = 'INTEREST__MUSIC_AND_AUDIO__REGGAE';
    /**
     * Interest | Music and Audio | Religious (Music and Audio).
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__RELIGIOUS__MUSIC_AND_AUDIO = 'INTEREST__MUSIC_AND_AUDIO__RELIGIOUS__MUSIC_AND_AUDIO';
    /**
     * Interest | Music and Audio | Rock Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__ROCK_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__ROCK_MUSIC';
    /**
     * Interest | Music and Audio | Songwriters/Folk.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__SONGWRITERS_FOLK = 'INTEREST__MUSIC_AND_AUDIO__SONGWRITERS_FOLK';
    /**
     * Interest | Music and Audio | Soundtracks, TV and Showtunes.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__SOUNDTRACKS_TV_AND_SHOWTUNES = 'INTEREST__MUSIC_AND_AUDIO__SOUNDTRACKS_TV_AND_SHOWTUNES';
    /**
     * Interest | Music and Audio | Sports Radio.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__SPORTS_RADIO = 'INTEREST__MUSIC_AND_AUDIO__SPORTS_RADIO';
    /**
     * Interest | Music and Audio | Talk Radio.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__TALK_RADIO = 'INTEREST__MUSIC_AND_AUDIO__TALK_RADIO';
    /**
     * Interest | Music and Audio | Urban Contemporary Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__URBAN_CONTEMPORARY_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__URBAN_CONTEMPORARY_MUSIC';
    /**
     * Interest | Music and Audio | Variety (Music and Audio).
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__VARIETY__MUSIC_AND_AUDIO = 'INTEREST__MUSIC_AND_AUDIO__VARIETY__MUSIC_AND_AUDIO';
    /**
     * Interest | Music and Audio | World/International Music.
     */
    public const CATEGORY_INTEREST__MUSIC_AND_AUDIO__WORLD_INTERNATIONAL_MUSIC = 'INTEREST__MUSIC_AND_AUDIO__WORLD_INTERNATIONAL_MUSIC';
    /**
     * Interest | News and Politics | Crime.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__CRIME = 'INTEREST__NEWS_AND_POLITICS__CRIME';
    /**
     * Interest | News and Politics | Disasters.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__DISASTERS = 'INTEREST__NEWS_AND_POLITICS__DISASTERS';
    /**
     * Interest | News and Politics | International News.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__INTERNATIONAL_NEWS = 'INTEREST__NEWS_AND_POLITICS__INTERNATIONAL_NEWS';
    /**
     * Interest | News and Politics | Law.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__LAW = 'INTEREST__NEWS_AND_POLITICS__LAW';
    /**
     * Interest | News and Politics | Local News.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__LOCAL_NEWS = 'INTEREST__NEWS_AND_POLITICS__LOCAL_NEWS';
    /**
     * Interest | News and Politics | National News.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__NATIONAL_NEWS = 'INTEREST__NEWS_AND_POLITICS__NATIONAL_NEWS';
    /**
     * Interest | News and Politics | Politics.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__POLITICS = 'INTEREST__NEWS_AND_POLITICS__POLITICS';
    /**
     * Interest | News and Politics | Weather.
     */
    public const CATEGORY_INTEREST__NEWS_AND_POLITICS__WEATHER = 'INTEREST__NEWS_AND_POLITICS__WEATHER';
    /**
     * Interest | Personal Finance | Frugal Living.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__FRUGAL_LIVING = 'INTEREST__PERSONAL_FINANCE__FRUGAL_LIVING';
    /**
     * Interest | Personal Finance | Insurance.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__INSURANCE = 'INTEREST__PERSONAL_FINANCE__INSURANCE';
    /**
     * Interest | Personal Finance | Personal Debt.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__PERSONAL_DEBT = 'INTEREST__PERSONAL_FINANCE__PERSONAL_DEBT';
    /**
     * Interest | Personal Finance | Personal Investing.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__PERSONAL_INVESTING = 'INTEREST__PERSONAL_FINANCE__PERSONAL_INVESTING';
    /**
     * Interest | Personal Finance | Personal Taxes.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__PERSONAL_TAXES = 'INTEREST__PERSONAL_FINANCE__PERSONAL_TAXES';
    /**
     * Interest | Personal Finance | Retirement Planning.
     */
    public const CATEGORY_INTEREST__PERSONAL_FINANCE__RETIREMENT_PLANNING = 'INTEREST__PERSONAL_FINANCE__RETIREMENT_PLANNING';
    /**
     * Interest | Pets | Birds.
     */
    public const CATEGORY_INTEREST__PETS__BIRDS = 'INTEREST__PETS__BIRDS';
    /**
     * Interest | Pets | Cats.
     */
    public const CATEGORY_INTEREST__PETS__CATS = 'INTEREST__PETS__CATS';
    /**
     * Interest | Pets | Dogs.
     */
    public const CATEGORY_INTEREST__PETS__DOGS = 'INTEREST__PETS__DOGS';
    /**
     * Interest | Pets | Fish and Aquariums.
     */
    public const CATEGORY_INTEREST__PETS__FISH_AND_AQUARIUMS = 'INTEREST__PETS__FISH_AND_AQUARIUMS';
    /**
     * Interest | Pets | Horses and Equine.
     */
    public const CATEGORY_INTEREST__PETS__HORSES_AND_EQUINE = 'INTEREST__PETS__HORSES_AND_EQUINE';
    /**
     * Interest | Pets | Large Animals.
     */
    public const CATEGORY_INTEREST__PETS__LARGE_ANIMALS = 'INTEREST__PETS__LARGE_ANIMALS';
    /**
     * Interest | Pets | Pet Adoptions.
     */
    public const CATEGORY_INTEREST__PETS__PET_ADOPTIONS = 'INTEREST__PETS__PET_ADOPTIONS';
    /**
     * Interest | Pets | Reptiles.
     */
    public const CATEGORY_INTEREST__PETS__REPTILES = 'INTEREST__PETS__REPTILES';
    /**
     * Interest | Pop Culture | Humor and Satire.
     */
    public const CATEGORY_INTEREST__POP_CULTURE__HUMOR_AND_SATIRE = 'INTEREST__POP_CULTURE__HUMOR_AND_SATIRE';
    /**
     * Interest | Real Estate | Apartments.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__APARTMENTS = 'INTEREST__REAL_ESTATE__APARTMENTS';
    /**
     * Interest | Real Estate | Developmental Sites.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__DEVELOPMENTAL_SITES = 'INTEREST__REAL_ESTATE__DEVELOPMENTAL_SITES';
    /**
     * Interest | Real Estate | Hotel Properties.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__HOTEL_PROPERTIES = 'INTEREST__REAL_ESTATE__HOTEL_PROPERTIES';
    /**
     * Interest | Real Estate | Houses.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__HOUSES = 'INTEREST__REAL_ESTATE__HOUSES';
    /**
     * Interest | Real Estate | Industrial Property.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__INDUSTRIAL_PROPERTY = 'INTEREST__REAL_ESTATE__INDUSTRIAL_PROPERTY';
    /**
     * Interest | Real Estate | Land and Farms.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__LAND_AND_FARMS = 'INTEREST__REAL_ESTATE__LAND_AND_FARMS';
    /**
     * Interest | Real Estate | Office Property.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__OFFICE_PROPERTY = 'INTEREST__REAL_ESTATE__OFFICE_PROPERTY';
    /**
     * Interest | Real Estate | Real Estate Buying and Selling.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__REAL_ESTATE_BUYING_AND_SELLING = 'INTEREST__REAL_ESTATE__REAL_ESTATE_BUYING_AND_SELLING';
    /**
     * Interest | Real Estate | Real Estate Renting and Leasing.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__REAL_ESTATE_RENTING_AND_LEASING = 'INTEREST__REAL_ESTATE__REAL_ESTATE_RENTING_AND_LEASING';
    /**
     * Interest | Real Estate | Retail Property.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__RETAIL_PROPERTY = 'INTEREST__REAL_ESTATE__RETAIL_PROPERTY';
    /**
     * Interest | Real Estate | Vacation Properties.
     */
    public const CATEGORY_INTEREST__REAL_ESTATE__VACATION_PROPERTIES = 'INTEREST__REAL_ESTATE__VACATION_PROPERTIES';
    /**
     * Interest | Sports | American Football.
     */
    public const CATEGORY_INTEREST__SPORTS__AMERICAN_FOOTBALL = 'INTEREST__SPORTS__AMERICAN_FOOTBALL';
    /**
     * Interest | Sports | Australian Rules Football.
     */
    public const CATEGORY_INTEREST__SPORTS__AUSTRALIAN_RULES_FOOTBALL = 'INTEREST__SPORTS__AUSTRALIAN_RULES_FOOTBALL';
    /**
     * Interest | Sports | Auto Racing.
     */
    public const CATEGORY_INTEREST__SPORTS__AUTO_RACING = 'INTEREST__SPORTS__AUTO_RACING';
    /**
     * Interest | Sports | Badminton.
     */
    public const CATEGORY_INTEREST__SPORTS__BADMINTON = 'INTEREST__SPORTS__BADMINTON';
    /**
     * Interest | Sports | Baseball.
     */
    public const CATEGORY_INTEREST__SPORTS__BASEBALL = 'INTEREST__SPORTS__BASEBALL';
    /**
     * Interest | Sports | Basketball.
     */
    public const CATEGORY_INTEREST__SPORTS__BASKETBALL = 'INTEREST__SPORTS__BASKETBALL';
    /**
     * Interest | Sports | Beach Volleyball.
     */
    public const CATEGORY_INTEREST__SPORTS__BEACH_VOLLEYBALL = 'INTEREST__SPORTS__BEACH_VOLLEYBALL';
    /**
     * Interest | Sports | Bodybuilding.
     */
    public const CATEGORY_INTEREST__SPORTS__BODYBUILDING = 'INTEREST__SPORTS__BODYBUILDING';
    /**
     * Interest | Sports | Bowling.
     */
    public const CATEGORY_INTEREST__SPORTS__BOWLING = 'INTEREST__SPORTS__BOWLING';
    /**
     * Interest | Sports | Boxing.
     */
    public const CATEGORY_INTEREST__SPORTS__BOXING = 'INTEREST__SPORTS__BOXING';
    /**
     * Interest | Sports | Cheerleading.
     */
    public const CATEGORY_INTEREST__SPORTS__CHEERLEADING = 'INTEREST__SPORTS__CHEERLEADING';
    /**
     * Interest | Sports | College Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__COLLEGE_SPORTS = 'INTEREST__SPORTS__COLLEGE_SPORTS';
    /**
     * Interest | Sports | Cricket.
     */
    public const CATEGORY_INTEREST__SPORTS__CRICKET = 'INTEREST__SPORTS__CRICKET';
    /**
     * Interest | Sports | Cycling.
     */
    public const CATEGORY_INTEREST__SPORTS__CYCLING = 'INTEREST__SPORTS__CYCLING';
    /**
     * Interest | Sports | Darts.
     */
    public const CATEGORY_INTEREST__SPORTS__DARTS = 'INTEREST__SPORTS__DARTS';
    /**
     * Interest | Sports | Disabled Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__DISABLED_SPORTS = 'INTEREST__SPORTS__DISABLED_SPORTS';
    /**
     * Interest | Sports | Diving.
     */
    public const CATEGORY_INTEREST__SPORTS__DIVING = 'INTEREST__SPORTS__DIVING';
    /**
     * Interest | Sports | Equine Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__EQUINE_SPORTS = 'INTEREST__SPORTS__EQUINE_SPORTS';
    /**
     * Interest | Sports | Extreme Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__EXTREME_SPORTS = 'INTEREST__SPORTS__EXTREME_SPORTS';
    /**
     * Interest | Sports | Fantasy Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__FANTASY_SPORTS = 'INTEREST__SPORTS__FANTASY_SPORTS';
    /**
     * Interest | Sports | Field Hockey.
     */
    public const CATEGORY_INTEREST__SPORTS__FIELD_HOCKEY = 'INTEREST__SPORTS__FIELD_HOCKEY';
    /**
     * Interest | Sports | Figure Skating.
     */
    public const CATEGORY_INTEREST__SPORTS__FIGURE_SKATING = 'INTEREST__SPORTS__FIGURE_SKATING';
    /**
     * Interest | Sports | Fishing Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__FISHING_SPORTS = 'INTEREST__SPORTS__FISHING_SPORTS';
    /**
     * Interest | Sports | Golf.
     */
    public const CATEGORY_INTEREST__SPORTS__GOLF = 'INTEREST__SPORTS__GOLF';
    /**
     * Interest | Sports | Gymnastics.
     */
    public const CATEGORY_INTEREST__SPORTS__GYMNASTICS = 'INTEREST__SPORTS__GYMNASTICS';
    /**
     * Interest | Sports | Hunting and Shooting.
     */
    public const CATEGORY_INTEREST__SPORTS__HUNTING_AND_SHOOTING = 'INTEREST__SPORTS__HUNTING_AND_SHOOTING';
    /**
     * Interest | Sports | Ice Hockey.
     */
    public const CATEGORY_INTEREST__SPORTS__ICE_HOCKEY = 'INTEREST__SPORTS__ICE_HOCKEY';
    /**
     * Interest | Sports | Inline Skating.
     */
    public const CATEGORY_INTEREST__SPORTS__INLINE_SKATING = 'INTEREST__SPORTS__INLINE_SKATING';
    /**
     * Interest | Sports | Lacrosse.
     */
    public const CATEGORY_INTEREST__SPORTS__LACROSSE = 'INTEREST__SPORTS__LACROSSE';
    /**
     * Interest | Sports | Martial Arts.
     */
    public const CATEGORY_INTEREST__SPORTS__MARTIAL_ARTS = 'INTEREST__SPORTS__MARTIAL_ARTS';
    /**
     * Interest | Sports | Olympic Sports.
     */
    public const CATEGORY_INTEREST__SPORTS__OLYMPIC_SPORTS = 'INTEREST__SPORTS__OLYMPIC_SPORTS';
    /**
     * Interest | Sports | Poker and Professional Gambling.
     */
    public const CATEGORY_INTEREST__SPORTS__POKER_AND_PROFESSIONAL_GAMBLING = 'INTEREST__SPORTS__POKER_AND_PROFESSIONAL_GAMBLING';
    /**
     * Interest | Sports | Rodeo.
     */
    public const CATEGORY_INTEREST__SPORTS__RODEO = 'INTEREST__SPORTS__RODEO';
    /**
     * Interest | Sports | Rowing.
     */
    public const CATEGORY_INTEREST__SPORTS__ROWING = 'INTEREST__SPORTS__ROWING';
    /**
     * Interest | Sports | Rugby.
     */
    public const CATEGORY_INTEREST__SPORTS__RUGBY = 'INTEREST__SPORTS__RUGBY';
    /**
     * Interest | Sports | Sailing.
     */
    public const CATEGORY_INTEREST__SPORTS__SAILING = 'INTEREST__SPORTS__SAILING';
    /**
     * Interest | Sports | Skiing.
     */
    public const CATEGORY_INTEREST__SPORTS__SKIING = 'INTEREST__SPORTS__SKIING';
    /**
     * Interest | Sports | Snooker/Pool/Billiards.
     */
    public const CATEGORY_INTEREST__SPORTS__SNOOKER_POOL_BILLIARDS = 'INTEREST__SPORTS__SNOOKER_POOL_BILLIARDS';
    /**
     * Interest | Sports | Soccer.
     */
    public const CATEGORY_INTEREST__SPORTS__SOCCER = 'INTEREST__SPORTS__SOCCER';
    /**
     * Interest | Sports | Softball.
     */
    public const CATEGORY_INTEREST__SPORTS__SOFTBALL = 'INTEREST__SPORTS__SOFTBALL';
    /**
     * Interest | Sports | Sports Equipment.
     */
    public const CATEGORY_INTEREST__SPORTS__SPORTS_EQUIPMENT = 'INTEREST__SPORTS__SPORTS_EQUIPMENT';
    /**
     * Interest | Sports | Squash.
     */
    public const CATEGORY_INTEREST__SPORTS__SQUASH = 'INTEREST__SPORTS__SQUASH';
    /**
     * Interest | Sports | Swimming.
     */
    public const CATEGORY_INTEREST__SPORTS__SWIMMING = 'INTEREST__SPORTS__SWIMMING';
    /**
     * Interest | Sports | Table Tennis.
     */
    public const CATEGORY_INTEREST__SPORTS__TABLE_TENNIS = 'INTEREST__SPORTS__TABLE_TENNIS';
    /**
     * Interest | Sports | Tennis.
     */
    public const CATEGORY_INTEREST__SPORTS__TENNIS = 'INTEREST__SPORTS__TENNIS';
    /**
     * Interest | Sports | Track and Field.
     */
    public const CATEGORY_INTEREST__SPORTS__TRACK_AND_FIELD = 'INTEREST__SPORTS__TRACK_AND_FIELD';
    /**
     * Interest | Sports | Volleyball.
     */
    public const CATEGORY_INTEREST__SPORTS__VOLLEYBALL = 'INTEREST__SPORTS__VOLLEYBALL';
    /**
     * Interest | Sports | Walking.
     */
    public const CATEGORY_INTEREST__SPORTS__WALKING = 'INTEREST__SPORTS__WALKING';
    /**
     * Interest | Sports | Water Polo.
     */
    public const CATEGORY_INTEREST__SPORTS__WATER_POLO = 'INTEREST__SPORTS__WATER_POLO';
    /**
     * Interest | Sports | Weightlifting.
     */
    public const CATEGORY_INTEREST__SPORTS__WEIGHTLIFTING = 'INTEREST__SPORTS__WEIGHTLIFTING';
    /**
     * Interest | Sports | Wrestling.
     */
    public const CATEGORY_INTEREST__SPORTS__WRESTLING = 'INTEREST__SPORTS__WRESTLING';
    /**
     * Interest | Style & Fashion | Beauty.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__BEAUTY = 'INTEREST__STYLE_AND_FASHION__BEAUTY';
    /**
     * Interest | Style & Fashion | Body Art.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__BODY_ART = 'INTEREST__STYLE_AND_FASHION__BODY_ART';
    /**
     * Interest | Style & Fashion | Children's Clothing.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__CHILDRENS_CLOTHING = 'INTEREST__STYLE_AND_FASHION__CHILDRENS_CLOTHING';
    /**
     * Interest | Style & Fashion | Designer Clothing.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__DESIGNER_CLOTHING = 'INTEREST__STYLE_AND_FASHION__DESIGNER_CLOTHING';
    /**
     * Interest | Style & Fashion | Fashion Trends.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__FASHION_TRENDS = 'INTEREST__STYLE_AND_FASHION__FASHION_TRENDS';
    /**
     * Interest | Style & Fashion | High Fashion.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__HIGH_FASHION = 'INTEREST__STYLE_AND_FASHION__HIGH_FASHION';
    /**
     * Interest | Style & Fashion | Men's Fashion.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__MENS_FASHION = 'INTEREST__STYLE_AND_FASHION__MENS_FASHION';
    /**
     * Interest | Style & Fashion | Personal Care.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__PERSONAL_CARE = 'INTEREST__STYLE_AND_FASHION__PERSONAL_CARE';
    /**
     * Interest | Style & Fashion | Street Style.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__STREET_STYLE = 'INTEREST__STYLE_AND_FASHION__STREET_STYLE';
    /**
     * Interest | Style & Fashion | Women's Fashion.
     */
    public const CATEGORY_INTEREST__STYLE_AND_FASHION__WOMENS_FASHION = 'INTEREST__STYLE_AND_FASHION__WOMENS_FASHION';
    /**
     * Interest | Technology & Computing | Artificial Intelligence.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__ARTIFICIAL_INTELLIGENCE = 'INTEREST__TECHNOLOGY_AND_COMPUTING__ARTIFICIAL_INTELLIGENCE';
    /**
     * Interest | Technology & Computing | Augmented Reality.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__AUGMENTED_REALITY = 'INTEREST__TECHNOLOGY_AND_COMPUTING__AUGMENTED_REALITY';
    /**
     * Interest | Technology & Computing | Computing.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__COMPUTING = 'INTEREST__TECHNOLOGY_AND_COMPUTING__COMPUTING';
    /**
     * Interest | Technology & Computing | Consumer Electronics.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__CONSUMER_ELECTRONICS = 'INTEREST__TECHNOLOGY_AND_COMPUTING__CONSUMER_ELECTRONICS';
    /**
     * Interest | Technology & Computing | Robotics.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__ROBOTICS = 'INTEREST__TECHNOLOGY_AND_COMPUTING__ROBOTICS';
    /**
     * Interest | Technology & Computing | Virtual Reality.
     */
    public const CATEGORY_INTEREST__TECHNOLOGY_AND_COMPUTING__VIRTUAL_REALITY = 'INTEREST__TECHNOLOGY_AND_COMPUTING__VIRTUAL_REALITY';
    /**
     * Interest | Television | Animation TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__ANIMATION_TV = 'INTEREST__TELEVISION__ANIMATION_TV';
    /**
     * Interest | Television | Children's TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__CHILDRENS_TV = 'INTEREST__TELEVISION__CHILDRENS_TV';
    /**
     * Interest | Television | Comedy TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__COMEDY_TV = 'INTEREST__TELEVISION__COMEDY_TV';
    /**
     * Interest | Television | Drama TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__DRAMA_TV = 'INTEREST__TELEVISION__DRAMA_TV';
    /**
     * Interest | Television | Factual TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__FACTUAL_TV = 'INTEREST__TELEVISION__FACTUAL_TV';
    /**
     * Interest | Television | Holiday TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__HOLIDAY_TV = 'INTEREST__TELEVISION__HOLIDAY_TV';
    /**
     * Interest | Television | Music TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__MUSIC_TV = 'INTEREST__TELEVISION__MUSIC_TV';
    /**
     * Interest | Television | Reality TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__REALITY_TV = 'INTEREST__TELEVISION__REALITY_TV';
    /**
     * Interest | Television | Science Fiction TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__SCIENCE_FICTION_TV = 'INTEREST__TELEVISION__SCIENCE_FICTION_TV';
    /**
     * Interest | Television | Soap Opera TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__SOAP_OPERA_TV = 'INTEREST__TELEVISION__SOAP_OPERA_TV';
    /**
     * Interest | Television | Special Interest TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__SPECIAL_INTEREST_TV = 'INTEREST__TELEVISION__SPECIAL_INTEREST_TV';
    /**
     * Interest | Television | Sports TV.
     */
    public const CATEGORY_INTEREST__TELEVISION__SPORTS_TV = 'INTEREST__TELEVISION__SPORTS_TV';
    /**
     * Interest | Travel | Adventure Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__ADVENTURE_TRAVEL = 'INTEREST__TRAVEL__ADVENTURE_TRAVEL';
    /**
     * Interest | Travel | Africa Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__AFRICA_TRAVEL = 'INTEREST__TRAVEL__AFRICA_TRAVEL';
    /**
     * Interest | Travel | Asia Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__ASIA_TRAVEL = 'INTEREST__TRAVEL__ASIA_TRAVEL';
    /**
     * Interest | Travel | Australia and Oceania Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__AUSTRALIA_AND_OCEANIA_TRAVEL = 'INTEREST__TRAVEL__AUSTRALIA_AND_OCEANIA_TRAVEL';
    /**
     * Interest | Travel | Beach Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__BEACH_TRAVEL = 'INTEREST__TRAVEL__BEACH_TRAVEL';
    /**
     * Interest | Travel | Camping.
     */
    public const CATEGORY_INTEREST__TRAVEL__CAMPING = 'INTEREST__TRAVEL__CAMPING';
    /**
     * Interest | Travel | Day Trips.
     */
    public const CATEGORY_INTEREST__TRAVEL__DAY_TRIPS = 'INTEREST__TRAVEL__DAY_TRIPS';
    /**
     * Interest | Travel | Europe Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__EUROPE_TRAVEL = 'INTEREST__TRAVEL__EUROPE_TRAVEL';
    /**
     * Interest | Travel | Family Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__FAMILY_TRAVEL = 'INTEREST__TRAVEL__FAMILY_TRAVEL';
    /**
     * Interest | Travel | North America Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__NORTH_AMERICA_TRAVEL = 'INTEREST__TRAVEL__NORTH_AMERICA_TRAVEL';
    /**
     * Interest | Travel | Polar Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__POLAR_TRAVEL = 'INTEREST__TRAVEL__POLAR_TRAVEL';
    /**
     * Interest | Travel | Road Trips.
     */
    public const CATEGORY_INTEREST__TRAVEL__ROAD_TRIPS = 'INTEREST__TRAVEL__ROAD_TRIPS';
    /**
     * Interest | Travel | South America Travel.
     */
    public const CATEGORY_INTEREST__TRAVEL__SOUTH_AMERICA_TRAVEL = 'INTEREST__TRAVEL__SOUTH_AMERICA_TRAVEL';
    /**
     * Interest | Video Gaming | Console Games.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING__CONSOLE_GAMES = 'INTEREST__VIDEO_GAMING__CONSOLE_GAMES';
    /**
     * Interest | Video Gaming | eSports.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING__ESPORTS = 'INTEREST__VIDEO_GAMING__ESPORTS';
    /**
     * Interest | Video Gaming | Mobile Games.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING__MOBILE_GAMES = 'INTEREST__VIDEO_GAMING__MOBILE_GAMES';
    /**
     * Interest | Video Gaming | PC Games.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING__PC_GAMES = 'INTEREST__VIDEO_GAMING__PC_GAMES';
    /**
     * Interest | Video Gaming | Video Game Genres.
     */
    public const CATEGORY_INTEREST__VIDEO_GAMING__VIDEO_GAME_GENRES = 'INTEREST__VIDEO_GAMING__VIDEO_GAME_GENRES';
    /**
     * Demographic | Education & Occupation | High school graduate.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__HIGH_SCHOOL_GRADUATE = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__HIGH_SCHOOL_GRADUATE';
    /**
     * Demographic | Education & Occupation | Associate's degree.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__ASSOCIATES_DEGREE = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__ASSOCIATES_DEGREE';
    /**
     * Demographic | Education & Occupation | Bachelor's degree.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__BACHELORS_DEGREE = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__BACHELORS_DEGREE';
    /**
     * Demographic | Education & Occupation | Graduate degree and above.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__GRADUATE_DEGREE_AND_ABOVE = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__GRADUATE_DEGREE_AND_ABOVE';
    /**
     * Demographic | Education & Occupation | Other.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION_OTHER = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION_OTHER';
    /**
     * Demographic | Age Range | 18-24.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE__18_TO_24 = 'DEMOGRAPHIC__AGE_RANGE__18_TO_24';
    /**
     * Demographic | Age Range | 25-34.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE__25_TO_34 = 'DEMOGRAPHIC__AGE_RANGE__25_TO_34';
    /**
     * Demographic | Age Range | 35-44.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE__35_TO_44 = 'DEMOGRAPHIC__AGE_RANGE__35_TO_44';
    /**
     * Demographic | Age Range | 45-54.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE__45_TO_54 = 'DEMOGRAPHIC__AGE_RANGE__45_TO_54';
    /**
     * Demographic | Age Range | 55+.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE__55_AND_ABOVE = 'DEMOGRAPHIC__AGE_RANGE__55_AND_ABOVE';
    /**
     * Demographic | Household Data | Less than $10,000.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_10000 = 'DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_10000';
    /**
     * Demographic | Household Data | $10,000-$49,999.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__10000_TO_49999 = 'DEMOGRAPHIC__HOUSEHOLD_DATA__10000_TO_49999';
    /**
     * Demographic | Household Data | $50,000-$149,999.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__50000_TO_149999 = 'DEMOGRAPHIC__HOUSEHOLD_DATA__50000_TO_149999';
    /**
     * Demographic | Household Data | $150,000-$249,999.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__150000_TO_249999 = 'DEMOGRAPHIC__HOUSEHOLD_DATA__150000_TO_249999';
    /**
     * Demographic | Household Data | $250,000+.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__250000_AND_ABOVE = 'DEMOGRAPHIC__HOUSEHOLD_DATA__250000_AND_ABOVE';
    /**
     * Deprecated: Demographic | Household Data | Less than $50,000.
     *
     * @deprecated
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_50000 = 'DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_50000';
    /**
     * Demographic | Household Data | 1 Adult.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__1_ADULT = 'DEMOGRAPHIC__HOUSEHOLD_DATA__1_ADULT';
    /**
     * Demographic | Household Data | 2 Adults.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__2_ADULTS = 'DEMOGRAPHIC__HOUSEHOLD_DATA__2_ADULTS';
    /**
     * Demographic | Household Data | 3+ Adults.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_ADULTS = 'DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_ADULTS';
    /**
     * Demographic | Household Data | 0 Child.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__0_CHILD = 'DEMOGRAPHIC__HOUSEHOLD_DATA__0_CHILD';
    /**
     * Demographic | Household Data | 1 Child.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__1_CHILD = 'DEMOGRAPHIC__HOUSEHOLD_DATA__1_CHILD';
    /**
     * Demographic | Household Data | 2 Children.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__2_CHILDREN = 'DEMOGRAPHIC__HOUSEHOLD_DATA__2_CHILDREN';
    /**
     * Demographic | Household Data | 3+ Children.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_CHILDREN = 'DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_CHILDREN';
    /**
     * Demographic | Education & Occupation | Retired.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__RETIRED = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__RETIRED';
    /**
     * Demographic | Education & Occupation | Student.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__STUDENT = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__STUDENT';
    /**
     * Demographic | Education & Occupation | Part-Time.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__PART_TIME = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__PART_TIME';
    /**
     * Demographic | Education & Occupation | Full-Time.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__FULL_TIME = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__FULL_TIME';
    /**
     * Demographic | Education & Occupation | Self-Employed.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__SELF_EMPLOYED = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__SELF_EMPLOYED';
    /**
     * Demographic | Education & Occupation | Unemployed / Job Seeker.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__UNEMPLOYED_JOB_SEEKER = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__UNEMPLOYED_JOB_SEEKER';
    /**
     * Purchase Intent | Beauty.
     */
    public const CATEGORY_PURCHASE_INTENT__BEAUTY = 'PURCHASE_INTENT__BEAUTY';
    /**
     * Purchase Intent | Clothing.
     */
    public const CATEGORY_PURCHASE_INTENT__CLOTHING = 'PURCHASE_INTENT__CLOTHING';
    /**
     * Purchase Intent | Credit Cards.
     */
    public const CATEGORY_PURCHASE_INTENT__CREDIT_CARDS = 'PURCHASE_INTENT__CREDIT_CARDS';
    /**
     * Purchase Intent | Electronics.
     */
    public const CATEGORY_PURCHASE_INTENT__ELECTRONICS = 'PURCHASE_INTENT__ELECTRONICS';
    /**
     * Purchase Intent | Home and Garden Products.
     */
    public const CATEGORY_PURCHASE_INTENT__HOME_AND_GARDEN_PRODUCTS = 'PURCHASE_INTENT__HOME_AND_GARDEN_PRODUCTS';
    /**
     * Purchase Intent | Household Appliances.
     */
    public const CATEGORY_PURCHASE_INTENT__HOUSEHOLD_APPLIANCES = 'PURCHASE_INTENT__HOUSEHOLD_APPLIANCES';
    /**
     * Purchase Intent | Insurance.
     */
    public const CATEGORY_PURCHASE_INTENT__INSURANCE = 'PURCHASE_INTENT__INSURANCE';
    /**
     * Purchase Intent | New / Pre-owned vehicles.
     */
    public const CATEGORY_PURCHASE_INTENT__NEW_OR_PRE_OWNED_VEHICLES = 'PURCHASE_INTENT__NEW_OR_PRE_OWNED_VEHICLES';
    /**
     * Purchase Intent | None of the above.
     */
    public const CATEGORY_PURCHASE_INTENT__NONE_OF_THE_ABOVE = 'PURCHASE_INTENT__NONE_OF_THE_ABOVE';
    /**
     * Digital Subscription Benefits | Access to Exclusive Content.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS__ACCESS_TO_EXCLUSIVE_CONTENT = 'DIGITAL_SUBSCRIPTION_BENEFITS__ACCESS_TO_EXCLUSIVE_CONTENT';
    /**
     * Digital Subscription Benefits | Anytime Cancellation.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS__ANYTIME_CANCELLATION = 'DIGITAL_SUBSCRIPTION_BENEFITS__ANYTIME_CANCELLATION';
    /**
     * Digital Subscription Benefits | Discounted Subscription Plans.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS__DISCOUNTED_SUBSCRIPTION_PLANS = 'DIGITAL_SUBSCRIPTION_BENEFITS__DISCOUNTED_SUBSCRIPTION_PLANS';
    /**
     * Digital Subscription Benefits | Early Access.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS__EARLY_ACCESS = 'DIGITAL_SUBSCRIPTION_BENEFITS__EARLY_ACCESS';
    /**
     * Digital Subscription Benefits | Shared Access.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS__SHARED_ACCESS = 'DIGITAL_SUBSCRIPTION_BENEFITS__SHARED_ACCESS';
    /**
     * Output only. Category of the answer based on the IAB Audience Taxonomy.
     *
     * @var string
     */
    public $category;
    /**
     * Output only. Text label used for aggregating responses by category in
     * analytics.
     *
     * @var string
     */
    public $categoryLabel;
    /**
     * Output only. Localized answer choice text displayed to readers.
     *
     * @var string
     */
    public $displayText;
    /**
     * Output only. Unique identifier of the answer choice within the question.
     *
     * @var int
     */
    public $id;
    /**
     * Output only. Category of the answer based on the IAB Audience Taxonomy.
     *
     * Accepted values: ANSWER_CATEGORY_UNSPECIFIED, INTEREST__ACADEMIC_INTERESTS,
     * INTEREST__AUTOMOTIVE, INTEREST__BOOKS_AND_LITERATURE,
     * INTEREST__BUSINESS_AND_FINANCE, INTEREST__CAREERS,
     * INTEREST__FAMILY_AND_RELATIONSHIPS, INTEREST__FINE_ART,
     * INTEREST__FOOD_AND_DRINK, INTEREST__HEALTH_AND_MEDICAL_SERVICES,
     * INTEREST__HEALTHY_LIVING, INTEREST__HOBBIES_AND_INTERESTS,
     * INTEREST__HOME_AND_GARDEN, INTEREST__MEDICAL_HEALTH, INTEREST__MOVIES,
     * INTEREST__MUSIC_AND_AUDIO, INTEREST__NEWS_AND_POLITICS,
     * INTEREST__PERSONAL_FINANCE, INTEREST__PETS,
     * INTEREST__PHARMACEUTICALS_CONDITIONS_AND_SYMPTOMS, INTEREST__POP_CULTURE,
     * INTEREST__REAL_ESTATE, INTEREST__SHOPPING, INTEREST__SPORTS,
     * INTEREST__STYLE_AND_FASHION, INTEREST__TECHNOLOGY_AND_COMPUTING,
     * INTEREST__TELEVISION, INTEREST__TRAVEL, INTEREST__VIDEO_GAMING,
     * INTEREST__ACADEMIC_INTERESTS__ARTS_AND_HUMANITIES,
     * INTEREST__ACADEMIC_INTERESTS__LANGUAGE_LEARNING,
     * INTEREST__ACADEMIC_INTERESTS__LIFE_SCIENCES,
     * INTEREST__ACADEMIC_INTERESTS__PHYSICAL_SCIENCE_AND_ENGINEERING,
     * INTEREST__ACADEMIC_INTERESTS__SOCIAL_SCIENCES,
     * INTEREST__AUTOMOTIVE__AUTO_BUYING_AND_SELLING,
     * INTEREST__AUTOMOTIVE__AUTO_SHOWS, INTEREST__AUTOMOTIVE__AUTO_TECHNOLOGY,
     * INTEREST__AUTOMOTIVE__BUDGET_CARS, INTEREST__AUTOMOTIVE__CAR_CULTURE,
     * INTEREST__AUTOMOTIVE__CLASSIC_CARS, INTEREST__AUTOMOTIVE__CONCEPT_CARS,
     * INTEREST__AUTOMOTIVE__DASH_CAM_VIDEOS,
     * INTEREST__AUTOMOTIVE__DRIVERLESS_CARS,
     * INTEREST__AUTOMOTIVE__GREEN_VEHICLES, INTEREST__AUTOMOTIVE__LUXURY_CARS,
     * INTEREST__AUTOMOTIVE__MOTORCYCLES, INTEREST__AUTOMOTIVE__PERFORMANCE_CARS,
     * INTEREST__AUTOMOTIVE__SCOOTERS,
     * INTEREST__BOOKS_AND_LITERATURE__ART_AND_PHOTOGRAPHY_BOOKS,
     * INTEREST__BOOKS_AND_LITERATURE__BIOGRAPHIES,
     * INTEREST__BOOKS_AND_LITERATURE__CHILDRENS_LITERATURE,
     * INTEREST__BOOKS_AND_LITERATURE__COMICS_AND_GRAPHIC_NOVELS,
     * INTEREST__BOOKS_AND_LITERATURE__COOKBOOKS,
     * INTEREST__BOOKS_AND_LITERATURE__FICTION,
     * INTEREST__BOOKS_AND_LITERATURE__POETRY,
     * INTEREST__BOOKS_AND_LITERATURE__TRAVEL_BOOKS,
     * INTEREST__BOOKS_AND_LITERATURE__YOUNG_ADULT_LITERATURE,
     * INTEREST__BUSINESS_AND_FINANCE__BUSINESS,
     * INTEREST__BUSINESS_AND_FINANCE__ECONOMY,
     * INTEREST__BUSINESS_AND_FINANCE__INDUSTRIES,
     * INTEREST__CAREERS__APPRENTICESHIPS, INTEREST__CAREERS__CAREER_ADVICE,
     * INTEREST__CAREERS__CAREER_PLANNING, INTEREST__CAREERS__REMOTE_WORKING,
     * INTEREST__CAREERS__VOCATIONAL_TRAINING,
     * INTEREST__EDUCATION__ADULT_EDUCATION,
     * INTEREST__EDUCATION__ONLINE_EDUCATION,
     * INTEREST__FAMILY_AND_RELATIONSHIPS__PARENTING, INTEREST__FINE_ART__COSTUME,
     * INTEREST__FINE_ART__DANCE, INTEREST__FINE_ART__DESIGN,
     * INTEREST__FINE_ART__DIGITAL_ARTS, INTEREST__FINE_ART__FINE_ART_PHOTOGRAPHY,
     * INTEREST__FINE_ART__MODERN_ART, INTEREST__FINE_ART__OPERA,
     * INTEREST__FINE_ART__THEATER, INTEREST__FOOD_AND_DRINK__ALCOHOLIC_BEVERAGES,
     * INTEREST__FOOD_AND_DRINK__BARBECUES_AND_GRILLING,
     * INTEREST__FOOD_AND_DRINK__COOKING,
     * INTEREST__FOOD_AND_DRINK__DESSERTS_AND_BAKING,
     * INTEREST__FOOD_AND_DRINK__DINING_OUT,
     * INTEREST__FOOD_AND_DRINK__FOOD_ALLERGIES,
     * INTEREST__FOOD_AND_DRINK__FOOD_MOVEMENTS,
     * INTEREST__FOOD_AND_DRINK__HEALTHY_COOKING_AND_EATING,
     * INTEREST__FOOD_AND_DRINK__NON_ALCOHOLIC_BEVERAGES,
     * INTEREST__FOOD_AND_DRINK__VEGAN_DIETS,
     * INTEREST__FOOD_AND_DRINK__VEGETARIAN_DIETS,
     * INTEREST__FOOD_AND_DRINK__WORLD_CUISINES,
     * INTEREST__HEALTH_AND_MEDICAL_SERVICES__HEALTH_AND_PHARMA,
     * INTEREST__HEALTHY_LIVING__CHILDRENS_HEALTH,
     * INTEREST__HEALTHY_LIVING__FITNESS_AND_EXERCISE,
     * INTEREST__HEALTHY_LIVING__MENS_HEALTH, INTEREST__HEALTHY_LIVING__NUTRITION,
     * INTEREST__HEALTHY_LIVING__SENIOR_HEALTH,
     * INTEREST__HEALTHY_LIVING__WEIGHT_LOSS, INTEREST__HEALTHY_LIVING__WELLNESS,
     * INTEREST__HEALTHY_LIVING__WOMENS_HEALTH,
     * INTEREST__HOBBIES_AND_INTERESTS__ANTIQUING_AND_ANTIQUES,
     * INTEREST__HOBBIES_AND_INTERESTS__ARTS_AND_CRAFTS,
     * INTEREST__HOBBIES_AND_INTERESTS__BEEKEEPING,
     * INTEREST__HOBBIES_AND_INTERESTS__BIRDWATCHING,
     * INTEREST__HOBBIES_AND_INTERESTS__CIGARS,
     * INTEREST__HOBBIES_AND_INTERESTS__COLLECTING,
     * INTEREST__HOBBIES_AND_INTERESTS__CONTENT_PRODUCTION,
     * INTEREST__HOBBIES_AND_INTERESTS__GAMES_AND_PUZZLES,
     * INTEREST__HOBBIES_AND_INTERESTS__GENEALOGY_AND_ANCESTRY,
     * INTEREST__HOBBIES_AND_INTERESTS__MAGIC_AND_ILLUSION,
     * INTEREST__HOBBIES_AND_INTERESTS__MODEL_TOYS,
     * INTEREST__HOBBIES_AND_INTERESTS__MUSICAL_INSTRUMENTS,
     * INTEREST__HOBBIES_AND_INTERESTS__PARANORMAL_PHENOMENA,
     * INTEREST__HOBBIES_AND_INTERESTS__RADIO_CONTROL,
     * INTEREST__HOBBIES_AND_INTERESTS__SCI_FI_AND_FANTASY,
     * INTEREST__HOBBIES_AND_INTERESTS__WORKSHOPS_AND_CLASSES,
     * INTEREST__HOME_AND_GARDEN__GARDENING,
     * INTEREST__HOME_AND_GARDEN__HOME_ENTERTAINING,
     * INTEREST__HOME_AND_GARDEN__HOME_IMPROVEMENT,
     * INTEREST__HOME_AND_GARDEN__INTERIOR_DECORATING,
     * INTEREST__HOME_AND_GARDEN__LANDSCAPING,
     * INTEREST__HOME_AND_GARDEN__OUTDOOR_DECORATING,
     * INTEREST__HOME_AND_GARDEN__REMODELING_AND_CONSTRUCTION,
     * INTEREST__HOME_AND_GARDEN__SMART_HOME,
     * INTEREST__MOVIES__ACTION_AND_ADVENTURE_MOVIES,
     * INTEREST__MOVIES__ANIMATION_MOVIES, INTEREST__MOVIES__COMEDY_MOVIES,
     * INTEREST__MOVIES__CRIME_AND_MYSTERY_MOVIES,
     * INTEREST__MOVIES__DOCUMENTARY_MOVIES, INTEREST__MOVIES__DRAMA_MOVIES,
     * INTEREST__MOVIES__FAMILY_AND_CHILDREN_MOVIES,
     * INTEREST__MOVIES__FANTASY_MOVIES, INTEREST__MOVIES__HORROR_MOVIES,
     * INTEREST__MOVIES__INDIE_AND_ARTHOUSE_MOVIES,
     * INTEREST__MOVIES__ROMANCE_MOVIES, INTEREST__MOVIES__SCIENCE_FICTION_MOVIES,
     * INTEREST__MOVIES__WORLD_MOVIES,
     * INTEREST__MUSIC_AND_AUDIO__ADULT_ALBUM_ALTERNATIVE,
     * INTEREST__MUSIC_AND_AUDIO__ADULT_CONTEMPORARY_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__ALTERNATIVE_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__BLUES,
     * INTEREST__MUSIC_AND_AUDIO__CHILDRENS_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__CLASSIC_HITS,
     * INTEREST__MUSIC_AND_AUDIO__CLASSICAL_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__COLLEGE_RADIO,
     * INTEREST__MUSIC_AND_AUDIO__COMEDY_MUSIC_AND_AUDIO,
     * INTEREST__MUSIC_AND_AUDIO__CONTEMPORARY_HITS_POP_TOP_40,
     * INTEREST__MUSIC_AND_AUDIO__COUNTRY_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__DANCE_AND_ELECTRONIC_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__GOSPEL_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__HIP_HOP_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__INSPIRATIONAL_NEW_AGE_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__JAZZ,
     * INTEREST__MUSIC_AND_AUDIO__OLDIES_ADULT_STANDARDS,
     * INTEREST__MUSIC_AND_AUDIO__RNB_SOUL_FUNK,
     * INTEREST__MUSIC_AND_AUDIO__REGGAE,
     * INTEREST__MUSIC_AND_AUDIO__RELIGIOUS__MUSIC_AND_AUDIO,
     * INTEREST__MUSIC_AND_AUDIO__ROCK_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__SONGWRITERS_FOLK,
     * INTEREST__MUSIC_AND_AUDIO__SOUNDTRACKS_TV_AND_SHOWTUNES,
     * INTEREST__MUSIC_AND_AUDIO__SPORTS_RADIO,
     * INTEREST__MUSIC_AND_AUDIO__TALK_RADIO,
     * INTEREST__MUSIC_AND_AUDIO__URBAN_CONTEMPORARY_MUSIC,
     * INTEREST__MUSIC_AND_AUDIO__VARIETY__MUSIC_AND_AUDIO,
     * INTEREST__MUSIC_AND_AUDIO__WORLD_INTERNATIONAL_MUSIC,
     * INTEREST__NEWS_AND_POLITICS__CRIME, INTEREST__NEWS_AND_POLITICS__DISASTERS,
     * INTEREST__NEWS_AND_POLITICS__INTERNATIONAL_NEWS,
     * INTEREST__NEWS_AND_POLITICS__LAW, INTEREST__NEWS_AND_POLITICS__LOCAL_NEWS,
     * INTEREST__NEWS_AND_POLITICS__NATIONAL_NEWS,
     * INTEREST__NEWS_AND_POLITICS__POLITICS,
     * INTEREST__NEWS_AND_POLITICS__WEATHER,
     * INTEREST__PERSONAL_FINANCE__FRUGAL_LIVING,
     * INTEREST__PERSONAL_FINANCE__INSURANCE,
     * INTEREST__PERSONAL_FINANCE__PERSONAL_DEBT,
     * INTEREST__PERSONAL_FINANCE__PERSONAL_INVESTING,
     * INTEREST__PERSONAL_FINANCE__PERSONAL_TAXES,
     * INTEREST__PERSONAL_FINANCE__RETIREMENT_PLANNING, INTEREST__PETS__BIRDS,
     * INTEREST__PETS__CATS, INTEREST__PETS__DOGS,
     * INTEREST__PETS__FISH_AND_AQUARIUMS, INTEREST__PETS__HORSES_AND_EQUINE,
     * INTEREST__PETS__LARGE_ANIMALS, INTEREST__PETS__PET_ADOPTIONS,
     * INTEREST__PETS__REPTILES, INTEREST__POP_CULTURE__HUMOR_AND_SATIRE,
     * INTEREST__REAL_ESTATE__APARTMENTS,
     * INTEREST__REAL_ESTATE__DEVELOPMENTAL_SITES,
     * INTEREST__REAL_ESTATE__HOTEL_PROPERTIES, INTEREST__REAL_ESTATE__HOUSES,
     * INTEREST__REAL_ESTATE__INDUSTRIAL_PROPERTY,
     * INTEREST__REAL_ESTATE__LAND_AND_FARMS,
     * INTEREST__REAL_ESTATE__OFFICE_PROPERTY,
     * INTEREST__REAL_ESTATE__REAL_ESTATE_BUYING_AND_SELLING,
     * INTEREST__REAL_ESTATE__REAL_ESTATE_RENTING_AND_LEASING,
     * INTEREST__REAL_ESTATE__RETAIL_PROPERTY,
     * INTEREST__REAL_ESTATE__VACATION_PROPERTIES,
     * INTEREST__SPORTS__AMERICAN_FOOTBALL,
     * INTEREST__SPORTS__AUSTRALIAN_RULES_FOOTBALL, INTEREST__SPORTS__AUTO_RACING,
     * INTEREST__SPORTS__BADMINTON, INTEREST__SPORTS__BASEBALL,
     * INTEREST__SPORTS__BASKETBALL, INTEREST__SPORTS__BEACH_VOLLEYBALL,
     * INTEREST__SPORTS__BODYBUILDING, INTEREST__SPORTS__BOWLING,
     * INTEREST__SPORTS__BOXING, INTEREST__SPORTS__CHEERLEADING,
     * INTEREST__SPORTS__COLLEGE_SPORTS, INTEREST__SPORTS__CRICKET,
     * INTEREST__SPORTS__CYCLING, INTEREST__SPORTS__DARTS,
     * INTEREST__SPORTS__DISABLED_SPORTS, INTEREST__SPORTS__DIVING,
     * INTEREST__SPORTS__EQUINE_SPORTS, INTEREST__SPORTS__EXTREME_SPORTS,
     * INTEREST__SPORTS__FANTASY_SPORTS, INTEREST__SPORTS__FIELD_HOCKEY,
     * INTEREST__SPORTS__FIGURE_SKATING, INTEREST__SPORTS__FISHING_SPORTS,
     * INTEREST__SPORTS__GOLF, INTEREST__SPORTS__GYMNASTICS,
     * INTEREST__SPORTS__HUNTING_AND_SHOOTING, INTEREST__SPORTS__ICE_HOCKEY,
     * INTEREST__SPORTS__INLINE_SKATING, INTEREST__SPORTS__LACROSSE,
     * INTEREST__SPORTS__MARTIAL_ARTS, INTEREST__SPORTS__OLYMPIC_SPORTS,
     * INTEREST__SPORTS__POKER_AND_PROFESSIONAL_GAMBLING, INTEREST__SPORTS__RODEO,
     * INTEREST__SPORTS__ROWING, INTEREST__SPORTS__RUGBY,
     * INTEREST__SPORTS__SAILING, INTEREST__SPORTS__SKIING,
     * INTEREST__SPORTS__SNOOKER_POOL_BILLIARDS, INTEREST__SPORTS__SOCCER,
     * INTEREST__SPORTS__SOFTBALL, INTEREST__SPORTS__SPORTS_EQUIPMENT,
     * INTEREST__SPORTS__SQUASH, INTEREST__SPORTS__SWIMMING,
     * INTEREST__SPORTS__TABLE_TENNIS, INTEREST__SPORTS__TENNIS,
     * INTEREST__SPORTS__TRACK_AND_FIELD, INTEREST__SPORTS__VOLLEYBALL,
     * INTEREST__SPORTS__WALKING, INTEREST__SPORTS__WATER_POLO,
     * INTEREST__SPORTS__WEIGHTLIFTING, INTEREST__SPORTS__WRESTLING,
     * INTEREST__STYLE_AND_FASHION__BEAUTY, INTEREST__STYLE_AND_FASHION__BODY_ART,
     * INTEREST__STYLE_AND_FASHION__CHILDRENS_CLOTHING,
     * INTEREST__STYLE_AND_FASHION__DESIGNER_CLOTHING,
     * INTEREST__STYLE_AND_FASHION__FASHION_TRENDS,
     * INTEREST__STYLE_AND_FASHION__HIGH_FASHION,
     * INTEREST__STYLE_AND_FASHION__MENS_FASHION,
     * INTEREST__STYLE_AND_FASHION__PERSONAL_CARE,
     * INTEREST__STYLE_AND_FASHION__STREET_STYLE,
     * INTEREST__STYLE_AND_FASHION__WOMENS_FASHION,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__ARTIFICIAL_INTELLIGENCE,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__AUGMENTED_REALITY,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__COMPUTING,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__CONSUMER_ELECTRONICS,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__ROBOTICS,
     * INTEREST__TECHNOLOGY_AND_COMPUTING__VIRTUAL_REALITY,
     * INTEREST__TELEVISION__ANIMATION_TV, INTEREST__TELEVISION__CHILDRENS_TV,
     * INTEREST__TELEVISION__COMEDY_TV, INTEREST__TELEVISION__DRAMA_TV,
     * INTEREST__TELEVISION__FACTUAL_TV, INTEREST__TELEVISION__HOLIDAY_TV,
     * INTEREST__TELEVISION__MUSIC_TV, INTEREST__TELEVISION__REALITY_TV,
     * INTEREST__TELEVISION__SCIENCE_FICTION_TV,
     * INTEREST__TELEVISION__SOAP_OPERA_TV,
     * INTEREST__TELEVISION__SPECIAL_INTEREST_TV, INTEREST__TELEVISION__SPORTS_TV,
     * INTEREST__TRAVEL__ADVENTURE_TRAVEL, INTEREST__TRAVEL__AFRICA_TRAVEL,
     * INTEREST__TRAVEL__ASIA_TRAVEL,
     * INTEREST__TRAVEL__AUSTRALIA_AND_OCEANIA_TRAVEL,
     * INTEREST__TRAVEL__BEACH_TRAVEL, INTEREST__TRAVEL__CAMPING,
     * INTEREST__TRAVEL__DAY_TRIPS, INTEREST__TRAVEL__EUROPE_TRAVEL,
     * INTEREST__TRAVEL__FAMILY_TRAVEL, INTEREST__TRAVEL__NORTH_AMERICA_TRAVEL,
     * INTEREST__TRAVEL__POLAR_TRAVEL, INTEREST__TRAVEL__ROAD_TRIPS,
     * INTEREST__TRAVEL__SOUTH_AMERICA_TRAVEL,
     * INTEREST__VIDEO_GAMING__CONSOLE_GAMES, INTEREST__VIDEO_GAMING__ESPORTS,
     * INTEREST__VIDEO_GAMING__MOBILE_GAMES, INTEREST__VIDEO_GAMING__PC_GAMES,
     * INTEREST__VIDEO_GAMING__VIDEO_GAME_GENRES,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__HIGH_SCHOOL_GRADUATE,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__ASSOCIATES_DEGREE,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__BACHELORS_DEGREE,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__GRADUATE_DEGREE_AND_ABOVE,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION_OTHER,
     * DEMOGRAPHIC__AGE_RANGE__18_TO_24, DEMOGRAPHIC__AGE_RANGE__25_TO_34,
     * DEMOGRAPHIC__AGE_RANGE__35_TO_44, DEMOGRAPHIC__AGE_RANGE__45_TO_54,
     * DEMOGRAPHIC__AGE_RANGE__55_AND_ABOVE,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_10000,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__10000_TO_49999,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__50000_TO_149999,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__150000_TO_249999,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__250000_AND_ABOVE,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__LESS_THAN_50000,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__1_ADULT,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__2_ADULTS,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_ADULTS,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__0_CHILD, DEMOGRAPHIC__HOUSEHOLD_DATA__1_CHILD,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__2_CHILDREN,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__3_AND_ABOVE_CHILDREN,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__RETIRED,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__STUDENT,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__PART_TIME,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__FULL_TIME,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__SELF_EMPLOYED,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__UNEMPLOYED_JOB_SEEKER,
     * PURCHASE_INTENT__BEAUTY, PURCHASE_INTENT__CLOTHING,
     * PURCHASE_INTENT__CREDIT_CARDS, PURCHASE_INTENT__ELECTRONICS,
     * PURCHASE_INTENT__HOME_AND_GARDEN_PRODUCTS,
     * PURCHASE_INTENT__HOUSEHOLD_APPLIANCES, PURCHASE_INTENT__INSURANCE,
     * PURCHASE_INTENT__NEW_OR_PRE_OWNED_VEHICLES,
     * PURCHASE_INTENT__NONE_OF_THE_ABOVE,
     * DIGITAL_SUBSCRIPTION_BENEFITS__ACCESS_TO_EXCLUSIVE_CONTENT,
     * DIGITAL_SUBSCRIPTION_BENEFITS__ANYTIME_CANCELLATION,
     * DIGITAL_SUBSCRIPTION_BENEFITS__DISCOUNTED_SUBSCRIPTION_PLANS,
     * DIGITAL_SUBSCRIPTION_BENEFITS__EARLY_ACCESS,
     * DIGITAL_SUBSCRIPTION_BENEFITS__SHARED_ACCESS
     *
     * @param self::CATEGORY_* $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    /**
     * @return self::CATEGORY_*
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Output only. Text label used for aggregating responses by category in
     * analytics.
     *
     * @param string $categoryLabel
     */
    public function setCategoryLabel($categoryLabel)
    {
        $this->categoryLabel = $categoryLabel;
    }
    /**
     * @return string
     */
    public function getCategoryLabel()
    {
        return $this->categoryLabel;
    }
    /**
     * Output only. Localized answer choice text displayed to readers.
     *
     * @param string $displayText
     */
    public function setDisplayText($displayText)
    {
        $this->displayText = $displayText;
    }
    /**
     * @return string
     */
    public function getDisplayText()
    {
        return $this->displayText;
    }
    /**
     * Output only. Unique identifier of the answer choice within the question.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AnswerChoiceTemplate::class, 'Google\Site_Kit_Dependencies\Google_Service_WebContentPublisher_AnswerChoiceTemplate');
