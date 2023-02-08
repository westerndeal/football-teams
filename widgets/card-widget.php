<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Football_Team_Card_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'football_teams';
    }

    public function get_title() {
        return __( 'Football Teams', 'football-teams' );
    }

    public function get_icon() {
        return 'fa fa-futbol-o';
    }

    public function get_categories() {
        return [ 'general' ];
    }


    protected function register_controls() {
        $this->start_controls_section(
            'section_football_team',
            [
                'label' => __( 'Content', 'football-teams' ),
            ]
        );

        $categories = get_categories( [
            'taxonomy' => 'league',
            'hide_empty' => false,
        ] );

        $category_options = [
            'all' => __( 'All', 'football-teams' ),
        ];

        foreach ( $categories as $category ) {
            $category_options[ $category->term_id ] = $category->name;
        }

        // echo "<pre>";
        // print_r($category_options);
        // exit;

         $this->add_control(
            'query_type',
            [
                'label' => __( 'Query Type', 'football-teams' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'keyword',
                'options' => [
                    'keyword' => __( 'Keyword', 'football-teams' ),
                    'number' => __( 'Number', 'football-teams' ),
                    'league' => __( 'League', 'football-teams' ),
                ],
            ]
        );

         $this->add_control(
            'fteam_keyword',
            [
                'label' => __( 'Keyword', 'football-teams' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'query_type' => 'keyword',
                ],
            ]
        );

         $this->add_control(
            'fteam_number',
            [
                'label' => __( 'Number', 'football-teams' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '',
                'condition' => [
                    'query_type' => 'number',
                ],
            ]
        );

         $this->add_control(
            'fteam_league',
            [
                'label' => __( 'League', 'football-teams' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $category_options,
                'condition' => [
                    'query_type' => 'league',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__( 'Style', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__( 'Color', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f00',
                'selectors' => [
                    '{{WRAPPER}} h4' => 'color: {{VALUE}}',
                ],
            ]
        );

       

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .elementor-list-widget-text, {{WRAPPER}} .elementor-list-widget-text ',
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'box_style',
                'selector' => '{{WRAPPER}} .box_post',
            ]
        );


        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if(isset($settings['fteam_league']) && ($settings['fteam_league']!= 'all')){
            $selected_posts = get_posts( [
                'post_type' => 'football_team',
                's' => isset($settings['fteam_keyword']) ? $settings['fteam_keyword'] : "",
                'include' => isset($settings['fteam_number']) ? $settings['fteam_number'] : "",
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => 'league',
                        'field' => 'term_id',
                        'terms' => isset($settings['fteam_league']) ? $settings['fteam_league'] : "",
                    ]
                ],
            ] );
        }else{
            $selected_posts = get_posts( [
                'post_type' => 'football_team',
                's' => isset($settings['fteam_keyword']) ? $settings['fteam_keyword'] : "",
                'include' => isset($settings['fteam_number']) ? $settings['fteam_number'] : "",
                'posts_per_page' => -1,
            ] );
        }

        // echo "<pre>";
        // print_r($selected_posts);
        // exit;

        if ( ! empty( $selected_posts ) ) {
            echo '<ul>';
            foreach ( $selected_posts as $post ) {
                $ID = $post->ID;
                $legueUrl =  get_post_meta($ID, 'football_teams_logo');
                //print_r($legueUrl);
                echo '<li class="elementor-list-widget-text box_post">';
                echo '<h4>' . $post->post_title . '</h4>';
                echo '<img src="'.$legueUrl[0].'"/>';
                // echo '<div>' . $post->post_content . '</div>';
                echo '</li>';
            }
            echo '</ul>';
        }
     }

     protected function content_template() {
        
     }

  //    private function get_posts() {
	 //    $query_args = [
	 //      'post_type'      => 'football_team',
	 //      'posts_per_page' => 5,
	 //    ];

	 //    return new WP_Query( $query_args );
	 // }

}