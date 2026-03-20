<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Page Content')
                    ->tabs([
                        // Basic Information Tab
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/featured')
                                    ->imageEditor()
                                    ->maxSize(5120),
                                Forms\Components\Toggle::make('is_published')
                                    ->required()
                                    ->default(false),
                                Forms\Components\TextInput::make('order')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        // Legacy Content Tab
                        Forms\Components\Tabs\Tab::make('Legacy Content')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->columnSpanFull()
                                    ->required(fn (Get $get) => $get('template_type') === 'content')
                                    ->helperText('Use this for simple text-based pages (required for legacy content)')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'link',
                                        'bulletList',
                                        'orderedList',
                                        'h2',
                                        'h3',
                                    ]),
                            ])
                            ->visible(fn (Get $get) => $get('template_type') === 'content'),

                        // Page Builder Tab
                        Forms\Components\Tabs\Tab::make('Page Builder')
                            ->schema([
                                Forms\Components\Select::make('template_type')
                                    ->label('Template Type')
                                    ->options([
                                        'content' => 'Legacy Content',
                                        'builder' => 'Page Builder',
                                    ])
                                    ->default('builder')
                                    ->required()
                                    ->live(),

                                Forms\Components\Builder::make('blocks')
                                    ->blocks([
                                        // Basic Blocks
                                        Block::make('text_block')
                                            ->label('Text Block')
                                            ->icon('heroicon-o-document-text')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Heading (Optional)'),
                                                Forms\Components\RichEditor::make('content')
                                                    ->required()
                                                    ->columnSpanFull()
                                                    ->toolbarButtons(['bold', 'italic', 'underline', 'link', 'bulletList', 'orderedList', 'h2', 'h3']),
                                                Forms\Components\Select::make('alignment')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('left'),
                                                Forms\Components\Select::make('containerWidth')
                                                    ->label('Container Width')
                                                    ->options([
                                                        'container' => 'Default Container',
                                                        'narrow' => 'Narrow (Prose)',
                                                        'wide' => 'Wide',
                                                        'full' => 'Full Width',
                                                    ])
                                                    ->default('container'),
                                            ]),

                                        Block::make('heading')
                                            ->label('Heading')
                                            ->icon('heroicon-o-bars-3-bottom-left')
                                            ->schema([
                                                Forms\Components\TextInput::make('text')
                                                    ->label('Heading Text')
                                                    ->required(),
                                                Forms\Components\Select::make('level')
                                                    ->label('Heading Level')
                                                    ->options([
                                                        'h1' => 'H1 (Main Title)',
                                                        'h2' => 'H2 (Section)',
                                                        'h3' => 'H3 (Subsection)',
                                                        'h4' => 'H4 (Minor)',
                                                    ])
                                                    ->default('h2')
                                                    ->required(),
                                                Forms\Components\TextInput::make('subtitle')
                                                    ->label('Subtitle (Optional)'),
                                                Forms\Components\Select::make('alignment')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('center'),
                                                Forms\Components\ColorPicker::make('color')
                                                    ->label('Text Color (Optional)'),
                                            ]),

                                        Block::make('image')
                                            ->label('Image')
                                            ->icon('heroicon-o-photo')
                                            ->schema([
                                                Forms\Components\FileUpload::make('image')
                                                    ->label('Image')
                                                    ->image()
                                                    ->required()
                                                    ->disk('public')
                                                    ->directory('pages/images')
                                                    ->imageEditor()
                                                    ->maxSize(5120),
                                                Forms\Components\TextInput::make('alt')
                                                    ->label('Alt Text')
                                                    ->required()
                                                    ->helperText('Describe the image for accessibility'),
                                                Forms\Components\TextInput::make('caption')
                                                    ->label('Caption (Optional)'),
                                                Forms\Components\Select::make('size')
                                                    ->options([
                                                        'small' => 'Small',
                                                        'medium' => 'Medium',
                                                        'large' => 'Large',
                                                        'full' => 'Full Width',
                                                    ])
                                                    ->default('medium'),
                                                Forms\Components\Select::make('alignment')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('center'),
                                                Forms\Components\Toggle::make('rounded')
                                                    ->label('Rounded Corners')
                                                    ->default(true),
                                            ]),

                                        Block::make('button')
                                            ->label('Button')
                                            ->icon('heroicon-o-cursor-arrow-rays')
                                            ->schema([
                                                Forms\Components\TextInput::make('text')
                                                    ->label('Button Text')
                                                    ->required(),
                                                Forms\Components\TextInput::make('url')
                                                    ->label('Button URL')
                                                    ->required()
                                                    ->url(),
                                                Forms\Components\Select::make('style')
                                                    ->options([
                                                        'primary' => 'Primary (Blue)',
                                                        'secondary' => 'Secondary (Gray)',
                                                        'accent' => 'Accent (Cyan)',
                                                        'outline' => 'Outline',
                                                    ])
                                                    ->default('primary'),
                                                Forms\Components\Select::make('size')
                                                    ->options([
                                                        'sm' => 'Small',
                                                        'md' => 'Medium',
                                                        'lg' => 'Large',
                                                    ])
                                                    ->default('md'),
                                                Forms\Components\Select::make('alignment')
                                                    ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                                                    ->default('center'),
                                                Forms\Components\Toggle::make('openInNewTab')
                                                    ->label('Open in New Tab')
                                                    ->default(false),
                                            ]),

                                        Block::make('spacer')
                                            ->label('Spacer')
                                            ->icon('heroicon-o-arrows-up-down')
                                            ->schema([
                                                Forms\Components\Select::make('height')
                                                    ->options([
                                                        'xs' => 'Extra Small (1rem)',
                                                        'sm' => 'Small (2rem)',
                                                        'md' => 'Medium (3rem)',
                                                        'lg' => 'Large (5rem)',
                                                        'xl' => 'Extra Large (8rem)',
                                                    ])
                                                    ->default('md'),
                                            ]),

                                        Block::make('divider')
                                            ->label('Divider')
                                            ->icon('heroicon-o-minus')
                                            ->schema([
                                                Forms\Components\Select::make('style')
                                                    ->options([
                                                        'solid' => 'Solid Line',
                                                        'dashed' => 'Dashed Line',
                                                        'dotted' => 'Dotted Line',
                                                        'gradient' => 'Gradient',
                                                    ])
                                                    ->default('solid'),
                                                Forms\Components\Select::make('width')
                                                    ->options([
                                                        'narrow' => 'Narrow',
                                                        'medium' => 'Medium',
                                                        'wide' => 'Wide',
                                                        'full' => 'Full Width',
                                                    ])
                                                    ->default('medium'),
                                            ]),

                                        // Section Blocks
                                        Block::make('hero')
                                            ->label('Hero Section')
                                            ->icon('heroicon-o-star')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Main Heading')
                                                    ->required(),
                                                Forms\Components\Textarea::make('subheading')
                                                    ->label('Subheading')
                                                    ->rows(2),
                                                Forms\Components\FileUpload::make('backgroundImage')
                                                    ->label('Background Image')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('pages/hero')
                                                    ->imageEditor(),
                                                Forms\Components\FileUpload::make('foregroundImage')
                                                    ->label('Foreground Image (Optional)')
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('pages/hero')
                                                    ->imageEditor(),
                                                Forms\Components\Select::make('overlay')
                                                    ->options([
                                                        'none' => 'No Overlay',
                                                        'dark' => 'Dark Overlay',
                                                        'gradient' => 'Gradient Overlay',
                                                        'primary' => 'Primary Color Overlay',
                                                    ])
                                                    ->default('gradient'),
                                                Forms\Components\TextInput::make('ctaText')
                                                    ->label('Call-to-Action Text'),
                                                Forms\Components\TextInput::make('ctaLink')
                                                    ->label('Call-to-Action Link'),
                                                Forms\Components\Select::make('height')
                                                    ->options([
                                                        'medium' => 'Medium (600px)',
                                                        'large' => 'Large (700px)',
                                                        'full' => 'Full Screen',
                                                    ])
                                                    ->default('large'),
                                                Forms\Components\Toggle::make('showParticles')
                                                    ->label('Show Animated Particles')
                                                    ->default(true),
                                            ]),

                                        Block::make('hero_split')
                                            ->label('Hero Split Section')
                                            ->icon('heroicon-o-rectangle-group')
                                            ->schema([
                                                Forms\Components\Fieldset::make('Heading Configuration')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('headingLine1')
                                                            ->label('Heading Line 1')
                                                            ->placeholder('Empowering Ghana\'s'),
                                                        Forms\Components\Select::make('headingLine1Color')
                                                            ->label('Line 1 Color')
                                                            ->options([
                                                                'primary' => 'Primary (Navy Blue)',
                                                                'purple' => 'Purple',
                                                                'accent' => 'Accent (Cyan)',
                                                                'secondary' => 'Secondary (Green)',
                                                                'gray' => 'Gray',
                                                            ])
                                                            ->default('primary'),
                                                        Forms\Components\TextInput::make('headingLine2')
                                                            ->label('Heading Line 2')
                                                            ->placeholder('Intellectual'),
                                                        Forms\Components\Select::make('headingLine2Color')
                                                            ->label('Line 2 Color')
                                                            ->options([
                                                                'primary' => 'Primary (Navy Blue)',
                                                                'purple' => 'Purple',
                                                                'accent' => 'Accent (Cyan)',
                                                                'secondary' => 'Secondary (Green)',
                                                                'gray' => 'Gray',
                                                            ])
                                                            ->default('purple'),
                                                        Forms\Components\TextInput::make('headingLine3')
                                                            ->label('Heading Line 3')
                                                            ->placeholder('Backbone'),
                                                        Forms\Components\Select::make('headingLine3Color')
                                                            ->label('Line 3 Color')
                                                            ->options([
                                                                'primary' => 'Primary (Navy Blue)',
                                                                'purple' => 'Purple',
                                                                'accent' => 'Accent (Cyan)',
                                                                'secondary' => 'Secondary (Green)',
                                                                'gray' => 'Gray',
                                                            ])
                                                            ->default('primary'),
                                                    ])
                                                    ->columns(2),
                                                Forms\Components\Textarea::make('subheading')
                                                    ->label('Subheading')
                                                    ->rows(3)
                                                    ->placeholder('Join a prestigious ecosystem...'),
                                                Forms\Components\Fieldset::make('Call to Action Buttons')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('primaryCtaText')
                                                            ->label('Primary Button Text')
                                                            ->placeholder('Apply for Membership'),
                                                        Forms\Components\TextInput::make('primaryCtaLink')
                                                            ->label('Primary Button Link')
                                                            ->placeholder('/membership/apply'),
                                                        Forms\Components\TextInput::make('secondaryCtaText')
                                                            ->label('Secondary Button Text')
                                                            ->placeholder('View Benefits'),
                                                        Forms\Components\TextInput::make('secondaryCtaLink')
                                                            ->label('Secondary Button Link')
                                                            ->placeholder('/membership/benefits'),
                                                    ])
                                                    ->columns(2),
                                                Forms\Components\Fieldset::make('Feature Box (Right Side)')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('featureBoxTopText')
                                                            ->label('Top Text (Small)')
                                                            ->placeholder('MADEMIIC'),
                                                        Forms\Components\TextInput::make('featureBoxMainText')
                                                            ->label('Main Text (Large)')
                                                            ->placeholder('NAIDMIIC RESEARCH'),
                                                        Forms\Components\TextInput::make('featureBoxBottomText')
                                                            ->label('Bottom Text (Medium)')
                                                            ->placeholder('SAFE WORK'),
                                                        Forms\Components\Select::make('featureBoxColor')
                                                            ->label('Feature Box Color')
                                                            ->options([
                                                                'teal' => 'Teal',
                                                                'primary' => 'Primary (Navy Blue)',
                                                                'purple' => 'Purple',
                                                                'accent' => 'Accent (Cyan)',
                                                                'secondary' => 'Secondary (Green)',
                                                            ])
                                                            ->default('teal'),
                                                    ]),
                                                Forms\Components\Fieldset::make('Layout Options')
                                                    ->schema([
                                                        Forms\Components\Select::make('height')
                                                            ->options([
                                                                'medium' => 'Medium (600px)',
                                                                'large' => 'Large (700px)',
                                                                'full' => 'Full Screen',
                                                            ])
                                                            ->default('large'),
                                                        Forms\Components\Toggle::make('showDecorations')
                                                            ->label('Show Background Decorations')
                                                            ->default(true),
                                                    ])
                                                    ->columns(2),
                                            ]),

                                        Block::make('stats')
                                            ->label('Statistics Section')
                                            ->icon('heroicon-o-chart-bar')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading (Optional)'),
                                                Forms\Components\Repeater::make('items')
                                                    ->label('Statistics')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('value')
                                                            ->label('Stat Value')
                                                            ->required()
                                                            ->placeholder('25'),
                                                        Forms\Components\TextInput::make('suffix')
                                                            ->label('Suffix')
                                                            ->placeholder('+ or % or Gbps'),
                                                        Forms\Components\TextInput::make('label')
                                                            ->label('Label')
                                                            ->required()
                                                            ->placeholder('Member Institutions'),
                                                    ])
                                                    ->minItems(2)
                                                    ->maxItems(6)
                                                    ->defaultItems(4)
                                                    ->collapsible()
                                                    ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                                                Forms\Components\Select::make('columns')
                                                    ->options([
                                                        '2' => '2 Columns',
                                                        '3' => '3 Columns',
                                                        '4' => '4 Columns',
                                                    ])
                                                    ->default('4'),
                                                Forms\Components\Toggle::make('animate')
                                                    ->label('Animate Counters')
                                                    ->default(true),
                                            ]),

                                        Block::make('two_column')
                                            ->label('Two Column Layout')
                                            ->icon('heroicon-o-view-columns')
                                            ->schema([
                                                Forms\Components\RichEditor::make('leftContent')
                                                    ->label('Left Column')
                                                    ->columnSpanFull()
                                                    ->required(),
                                                Forms\Components\RichEditor::make('rightContent')
                                                    ->label('Right Column')
                                                    ->columnSpanFull()
                                                    ->required(),
                                                Forms\Components\Select::make('ratio')
                                                    ->label('Column Ratio')
                                                    ->options([
                                                        '50-50' => '50% / 50%',
                                                        '60-40' => '60% / 40%',
                                                        '40-60' => '40% / 60%',
                                                        '70-30' => '70% / 30%',
                                                        '30-70' => '30% / 70%',
                                                    ])
                                                    ->default('50-50'),
                                                Forms\Components\Toggle::make('reverseOnMobile')
                                                    ->label('Reverse Order on Mobile')
                                                    ->default(false),
                                            ]),

                                        Block::make('card_grid')
                                            ->label('Card Grid')
                                            ->icon('heroicon-o-squares-2x2')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading (Optional)'),
                                                Forms\Components\Repeater::make('cards')
                                                    ->schema([
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Card Image')
                                                            ->image()
                                                            ->disk('public')
                                                            ->directory('pages/cards')
                                                            ->imageEditor(),
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Card Title')
                                                            ->required(),
                                                        Forms\Components\Textarea::make('description')
                                                            ->label('Card Description')
                                                            ->rows(3),
                                                        Forms\Components\TextInput::make('link')
                                                            ->label('Link URL (Optional)'),
                                                    ])
                                                    ->minItems(1)
                                                    ->maxItems(12)
                                                    ->collapsible()
                                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                                Forms\Components\Select::make('columns')
                                                    ->options([
                                                        '2' => '2 Columns',
                                                        '3' => '3 Columns',
                                                        '4' => '4 Columns',
                                                    ])
                                                    ->default('3'),
                                            ]),

                                        Block::make('icon_cards')
                                            ->label('Icon Cards Section')
                                            ->icon('heroicon-o-rectangle-stack')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading (Optional)'),
                                                Forms\Components\Textarea::make('subheading')
                                                    ->label('Section Subheading (Optional)')
                                                    ->rows(2),
                                                Forms\Components\Repeater::make('cards')
                                                    ->label('Cards')
                                                    ->schema([
                                                        Forms\Components\Select::make('icon')
                                                            ->label('Icon')
                                                            ->options([
                                                                'rocket' => 'Rocket',
                                                                'eye' => 'Eye',
                                                                'star' => 'Star',
                                                                'heart' => 'Heart',
                                                                'lightbulb' => 'Lightbulb',
                                                                'target' => 'Target',
                                                                'shield' => 'Shield',
                                                                'users' => 'Users',
                                                            ])
                                                            ->default('star'),
                                                        Forms\Components\ColorPicker::make('iconColor')
                                                            ->label('Icon Color')
                                                            ->default('#3B82F6'),
                                                        Forms\Components\ColorPicker::make('borderColor')
                                                            ->label('Top Border Color')
                                                            ->default('#3B82F6'),
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Card Title')
                                                            ->required(),
                                                        Forms\Components\Textarea::make('description')
                                                            ->label('Card Description')
                                                            ->rows(4)
                                                            ->required(),
                                                        Forms\Components\TextInput::make('link')
                                                            ->label('Link URL (Optional)'),
                                                        Forms\Components\TextInput::make('linkText')
                                                            ->label('Link Text (Optional)')
                                                            ->placeholder('Learn more'),
                                                    ])
                                                    ->minItems(1)
                                                    ->maxItems(4)
                                                    ->defaultItems(2)
                                                    ->collapsible()
                                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                                                Forms\Components\Select::make('columns')
                                                    ->label('Columns')
                                                    ->options([
                                                        '1' => '1 Column',
                                                        '2' => '2 Columns',
                                                        '3' => '3 Columns',
                                                        '4' => '4 Columns',
                                                    ])
                                                    ->default('2'),
                                            ]),

                                        // Form Blocks
                                        Block::make('contact_form')
                                            ->label('Contact Form')
                                            ->icon('heroicon-o-envelope')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Form Heading')
                                                    ->default('Contact Us'),
                                                Forms\Components\Textarea::make('description')
                                                    ->label('Form Description')
                                                    ->rows(2),
                                                Forms\Components\TextInput::make('submitText')
                                                    ->label('Submit Button Text')
                                                    ->default('Send Message'),
                                                Forms\Components\TextInput::make('emailTo')
                                                    ->label('Send Submissions To')
                                                    ->email()
                                                    ->required()
                                                    ->helperText('Email address to receive form submissions'),
                                                Forms\Components\Toggle::make('showPhone')
                                                    ->label('Include Phone Field')
                                                    ->default(false),
                                                Forms\Components\Toggle::make('showCompany')
                                                    ->label('Include Organization Field')
                                                    ->default(true),
                                            ]),

                                        Block::make('search_box')
                                            ->label('Search Box')
                                            ->icon('heroicon-o-magnifying-glass')
                                            ->schema([
                                                Forms\Components\TextInput::make('placeholder')
                                                    ->label('Placeholder Text')
                                                    ->default('Search...'),
                                                Forms\Components\Select::make('searchScope')
                                                    ->label('Search In')
                                                    ->options([
                                                        'pages' => 'Pages Only',
                                                        'news' => 'News Only',
                                                        'events' => 'Events Only',
                                                        'all' => 'All Content',
                                                    ])
                                                    ->default('all'),
                                                Forms\Components\Select::make('size')
                                                    ->options([
                                                        'sm' => 'Small',
                                                        'md' => 'Medium',
                                                        'lg' => 'Large',
                                                    ])
                                                    ->default('md'),
                                            ]),

                                        // Dynamic Content Blocks
                                        Block::make('dynamic_news')
                                            ->label('Latest News (Dynamic)')
                                            ->icon('heroicon-o-newspaper')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading')
                                                    ->default('Latest News'),
                                                Forms\Components\Textarea::make('subheading')
                                                    ->label('Subheading (Optional)')
                                                    ->rows(2),
                                                Forms\Components\TextInput::make('limit')
                                                    ->label('Number of Items')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(12)
                                                    ->default(3),
                                                Forms\Components\Select::make('layout')
                                                    ->options([
                                                        'grid' => 'Grid Layout',
                                                        'list' => 'List Layout',
                                                        'carousel' => 'Carousel',
                                                    ])
                                                    ->default('grid')
                                                    ->live(),
                                                Forms\Components\Select::make('columns')
                                                    ->label('Grid Columns')
                                                    ->options([
                                                        '2' => '2 Columns',
                                                        '3' => '3 Columns',
                                                        '4' => '4 Columns',
                                                    ])
                                                    ->default('3')
                                                    ->visible(fn (Get $get) => $get('layout') === 'grid'),
                                                Forms\Components\Toggle::make('showImage')
                                                    ->label('Show Featured Images')
                                                    ->default(true),
                                                Forms\Components\Toggle::make('showExcerpt')
                                                    ->label('Show Excerpt')
                                                    ->default(true),
                                                Forms\Components\Toggle::make('showDate')
                                                    ->label('Show Publication Date')
                                                    ->default(true),
                                                Forms\Components\Toggle::make('showReadMore')
                                                    ->label('Show Read More Link')
                                                    ->default(true),
                                            ]),

                                        Block::make('dynamic_events')
                                            ->label('Upcoming Events (Dynamic)')
                                            ->icon('heroicon-o-calendar-days')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading')
                                                    ->default('Upcoming Events'),
                                                Forms\Components\TextInput::make('limit')
                                                    ->label('Number of Events')
                                                    ->numeric()
                                                    ->minValue(1)
                                                    ->maxValue(12)
                                                    ->default(4),
                                                Forms\Components\Select::make('layout')
                                                    ->options([
                                                        'grid' => 'Grid Layout',
                                                        'list' => 'List Layout',
                                                        'timeline' => 'Timeline',
                                                    ])
                                                    ->default('grid'),
                                                Forms\Components\Select::make('filter')
                                                    ->label('Show Events')
                                                    ->options([
                                                        'upcoming' => 'Upcoming Only',
                                                        'past' => 'Past Only',
                                                        'all' => 'All Events',
                                                    ])
                                                    ->default('upcoming'),
                                                Forms\Components\Toggle::make('showLocation')
                                                    ->label('Show Event Location')
                                                    ->default(true),
                                            ]),

                                        Block::make('dynamic_services')
                                            ->label('Services (Dynamic)')
                                            ->icon('heroicon-o-cpu-chip')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading')
                                                    ->default('Our Services'),
                                                Forms\Components\Textarea::make('subheading')
                                                    ->label('Subheading (Optional)')
                                                    ->rows(2),
                                                Forms\Components\Select::make('limit')
                                                    ->label('Number of Services')
                                                    ->options([
                                                        '3' => '3 Services',
                                                        '6' => '6 Services',
                                                        '9' => '9 Services',
                                                        'all' => 'All Services',
                                                    ])
                                                    ->default('6'),
                                                Forms\Components\Select::make('layout')
                                                    ->options([
                                                        'grid' => 'Grid Layout',
                                                        'list' => 'List Layout',
                                                    ])
                                                    ->default('grid'),
                                                Forms\Components\Toggle::make('showIcons')
                                                    ->label('Show Service Icons')
                                                    ->default(true),
                                                Forms\Components\Toggle::make('showDescription')
                                                    ->label('Show Descriptions')
                                                    ->default(true),
                                            ]),

                                        Block::make('dynamic_members')
                                            ->label('Member Institutions (Dynamic)')
                                            ->icon('heroicon-o-building-library')
                                            ->schema([
                                                Forms\Components\TextInput::make('heading')
                                                    ->label('Section Heading')
                                                    ->default('Our Members'),
                                                Forms\Components\Select::make('display')
                                                    ->options([
                                                        'grid' => 'Grid with Logos',
                                                        'logos' => 'Logos Only',
                                                        'list' => 'Detailed List',
                                                    ])
                                                    ->default('grid'),
                                                Forms\Components\Select::make('limit')
                                                    ->label('Number to Show')
                                                    ->options([
                                                        '6' => '6 Members',
                                                        '12' => '12 Members',
                                                        '24' => '24 Members',
                                                        'all' => 'All Members',
                                                    ])
                                                    ->default('12'),
                                            ]),
                                    ])
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->minItems(0)
                                    ->addActionLabel('Add Block')
                                    ->blockNumbers(false),
                            ])
                            ->visible(fn (Get $get) => $get('template_type') === 'builder'),

                        // SEO Tab
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->helperText('Leave empty to use page title'),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->helperText('Leave empty to use excerpt'),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('featured_image'),
                Tables\Columns\BadgeColumn::make('template_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'builder',
                        'secondary' => 'content',
                    ])
                    ->icons([
                        'heroicon-o-cube' => 'builder',
                        'heroicon-o-document-text' => 'content',
                    ]),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('visual_editor')
                    ->label('Page Builder')
                    ->icon('heroicon-o-cube')
                    ->color('primary')
                    ->url(fn (Page $record): string => config('app.frontend_url', 'http://localhost:3000') . "/elementor/{$record->id}")
                    ->openUrlInNewTab()
                    ->visible(fn (Page $record): bool => $record->template_type === 'builder'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
