--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

-- Started on 2024-12-05 15:33:18

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 273 (class 1259 OID 16952)
-- Name: archive_news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.archive_news (
    id bigint NOT NULL,
    file character varying(255) NOT NULL,
    link character varying(255),
    type character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    "order" integer NOT NULL,
    status boolean NOT NULL,
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.archive_news OWNER TO postgres;

--
-- TOC entry 272 (class 1259 OID 16951)
-- Name: archive_news_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.archive_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.archive_news_id_seq OWNER TO postgres;

--
-- TOC entry 5176 (class 0 OID 0)
-- Dependencies: 272
-- Name: archive_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.archive_news_id_seq OWNED BY public.archive_news.id;


--
-- TOC entry 271 (class 1259 OID 16916)
-- Name: banner; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.banner (
    id bigint NOT NULL,
    image character varying(255) NOT NULL,
    menu_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.banner OWNER TO postgres;

--
-- TOC entry 270 (class 1259 OID 16915)
-- Name: banner_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.banner_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.banner_id_seq OWNER TO postgres;

--
-- TOC entry 5177 (class 0 OID 0)
-- Dependencies: 270
-- Name: banner_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.banner_id_seq OWNED BY public.banner.id;


--
-- TOC entry 225 (class 1259 OID 16698)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16705)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 16833)
-- Name: card_links; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.card_links (
    id bigint NOT NULL,
    card_id bigint NOT NULL,
    link_name character varying(255),
    link character varying(255),
    file character varying(255),
    "order" character varying(255) NOT NULL,
    status smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.card_links OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 16832)
-- Name: card_links_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.card_links_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.card_links_id_seq OWNER TO postgres;

--
-- TOC entry 5178 (class 0 OID 0)
-- Dependencies: 252
-- Name: card_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.card_links_id_seq OWNED BY public.card_links.id;


--
-- TOC entry 249 (class 1259 OID 16815)
-- Name: cards; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cards (
    id bigint NOT NULL,
    menu_id bigint NOT NULL,
    page_section_id bigint NOT NULL,
    card_title character varying(255) NOT NULL,
    card_description character varying(255),
    image character varying(255),
    more_link character varying(255),
    "order" character varying(255) NOT NULL,
    status smallint NOT NULL,
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    hindi_description character varying(255),
    khasi_description character varying(255),
    other_description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cards OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 16814)
-- Name: cards_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cards_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cards_id_seq OWNER TO postgres;

--
-- TOC entry 5179 (class 0 OID 0)
-- Dependencies: 248
-- Name: cards_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cards_id_seq OWNED BY public.cards.id;


--
-- TOC entry 245 (class 1259 OID 16797)
-- Name: carousel; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.carousel (
    id bigint NOT NULL,
    image character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    link character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    alt character varying(255) NOT NULL,
    "order" integer NOT NULL,
    status smallint NOT NULL,
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.carousel OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 16796)
-- Name: carousel_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.carousel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.carousel_id_seq OWNER TO postgres;

--
-- TOC entry 5180 (class 0 OID 0)
-- Dependencies: 244
-- Name: carousel_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.carousel_id_seq OWNED BY public.carousel.id;


--
-- TOC entry 231 (class 1259 OID 16730)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16729)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5181 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 261 (class 1259 OID 16873)
-- Name: footer; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.footer (
    id bigint NOT NULL,
    type character varying(255) DEFAULT 'text'::character varying NOT NULL,
    content text,
    link character varying(255),
    "order" smallint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    link_title character varying(30) DEFAULT NULL::character varying,
    CONSTRAINT footer_type_check CHECK (((type)::text = ANY ((ARRAY['text'::character varying, 'link'::character varying, 'logo'::character varying])::text[])))
);


ALTER TABLE public.footer OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 16872)
-- Name: footer_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.footer_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.footer_id_seq OWNER TO postgres;

--
-- TOC entry 5182 (class 0 OID 0)
-- Dependencies: 260
-- Name: footer_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.footer_id_seq OWNED BY public.footer.id;


--
-- TOC entry 255 (class 1259 OID 16842)
-- Name: gallery; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gallery (
    id bigint NOT NULL,
    parent_id integer NOT NULL,
    gallery_name character varying(255) NOT NULL,
    gallery_image character varying(255) NOT NULL,
    gallery_description character varying(255) NOT NULL,
    link character varying(255) NOT NULL,
    meta_title character varying(255) NOT NULL,
    meta_description character varying(255) NOT NULL,
    meta_keywords character varying(255) NOT NULL,
    "order" character varying(255) NOT NULL,
    status smallint NOT NULL,
    hindi_name character varying(255),
    khasi_name character varying(255),
    other_name character varying(255),
    hindi_description character varying(255),
    khasi_description character varying(255),
    other_description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.gallery OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 16841)
-- Name: gallery_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gallery_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.gallery_id_seq OWNER TO postgres;

--
-- TOC entry 5183 (class 0 OID 0)
-- Dependencies: 254
-- Name: gallery_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gallery_id_seq OWNED BY public.gallery.id;


--
-- TOC entry 257 (class 1259 OID 16851)
-- Name: govt_websites; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.govt_websites (
    id bigint NOT NULL,
    image character varying(255) NOT NULL,
    link character varying(255) NOT NULL,
    "order" smallint DEFAULT '1'::smallint NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.govt_websites OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 16850)
-- Name: govt_websites_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.govt_websites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.govt_websites_id_seq OWNER TO postgres;

--
-- TOC entry 5184 (class 0 OID 0)
-- Dependencies: 256
-- Name: govt_websites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.govt_websites_id_seq OWNED BY public.govt_websites.id;


--
-- TOC entry 239 (class 1259 OID 16772)
-- Name: home; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.home (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    more_link character varying(255),
    image character varying(255),
    image_decsription character varying(255),
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    hindi_description character varying(255),
    khasi_description character varying(255),
    other_description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.home OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 16771)
-- Name: home_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.home_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.home_id_seq OWNER TO postgres;

--
-- TOC entry 5185 (class 0 OID 0)
-- Dependencies: 238
-- Name: home_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.home_id_seq OWNED BY public.home.id;


--
-- TOC entry 229 (class 1259 OID 16722)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16713)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16712)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5186 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 251 (class 1259 OID 16824)
-- Name: latest_news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.latest_news (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    file character varying(255),
    link character varying(255),
    meta_keywords character varying(255),
    "order" character varying(255) NOT NULL,
    status smallint NOT NULL,
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.latest_news OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 16823)
-- Name: latest_news_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.latest_news_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.latest_news_id_seq OWNER TO postgres;

--
-- TOC entry 5187 (class 0 OID 0)
-- Dependencies: 250
-- Name: latest_news_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.latest_news_id_seq OWNED BY public.latest_news.id;


--
-- TOC entry 237 (class 1259 OID 16761)
-- Name: menu; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.menu (
    id bigint NOT NULL,
    menu_name character varying(255) NOT NULL,
    menu_master smallint NOT NULL,
    "order" smallint DEFAULT '1'::smallint NOT NULL,
    parent bigint NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL,
    hindi_name character varying(255),
    khasi_name character varying(255),
    other_name character varying(255)
);


ALTER TABLE public.menu OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 16760)
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.menu_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menu_id_seq OWNER TO postgres;

--
-- TOC entry 5188 (class 0 OID 0)
-- Dependencies: 236
-- Name: menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.menu_id_seq OWNED BY public.menu.id;


--
-- TOC entry 235 (class 1259 OID 16754)
-- Name: menu_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.menu_master (
    id bigint NOT NULL,
    menu_name character varying(255) NOT NULL
);


ALTER TABLE public.menu_master OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 16753)
-- Name: menu_master_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.menu_master_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menu_master_id_seq OWNER TO postgres;

--
-- TOC entry 5189 (class 0 OID 0)
-- Dependencies: 234
-- Name: menu_master_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.menu_master_id_seq OWNED BY public.menu_master.id;


--
-- TOC entry 218 (class 1259 OID 16657)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 16656)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5190 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 275 (class 1259 OID 16961)
-- Name: newsletter; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.newsletter (
    id bigint NOT NULL,
    menu_id integer NOT NULL,
    page_section_id integer NOT NULL,
    english_title character varying(255),
    hindi_title character varying(255),
    khasi_title character varying(255),
    date date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.newsletter OWNER TO postgres;

--
-- TOC entry 274 (class 1259 OID 16960)
-- Name: newsletter_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.newsletter_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.newsletter_id_seq OWNER TO postgres;

--
-- TOC entry 5191 (class 0 OID 0)
-- Dependencies: 274
-- Name: newsletter_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.newsletter_id_seq OWNED BY public.newsletter.id;


--
-- TOC entry 277 (class 1259 OID 16970)
-- Name: newsletterdetails; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.newsletterdetails (
    id bigint NOT NULL,
    newsletterid bigint NOT NULL,
    menu_id integer,
    page_section_id integer,
    english_title character varying(255),
    hindi_title character varying(255),
    khasi_title character varying(255),
    fromdate date,
    todate date,
    date date,
    filepath character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.newsletterdetails OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 16969)
-- Name: newslettersdetails_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.newslettersdetails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.newslettersdetails_id_seq OWNER TO postgres;

--
-- TOC entry 5192 (class 0 OID 0)
-- Dependencies: 276
-- Name: newslettersdetails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.newslettersdetails_id_seq OWNED BY public.newsletterdetails.id;


--
-- TOC entry 259 (class 1259 OID 16862)
-- Name: page_section; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.page_section (
    id bigint NOT NULL,
    menu_id integer NOT NULL,
    page_section_name character varying(255) NOT NULL,
    page_section_id bigint NOT NULL,
    "order" smallint DEFAULT '1'::smallint NOT NULL,
    status smallint DEFAULT '1'::smallint NOT NULL
);


ALTER TABLE public.page_section OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 16861)
-- Name: page_section_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.page_section_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.page_section_id_seq OWNER TO postgres;

--
-- TOC entry 5193 (class 0 OID 0)
-- Dependencies: 258
-- Name: page_section_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.page_section_id_seq OWNED BY public.page_section.id;


--
-- TOC entry 265 (class 1259 OID 16891)
-- Name: page_section_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.page_section_master (
    id bigint NOT NULL,
    page_section_name character varying(255) NOT NULL,
    icon character varying(255)
);


ALTER TABLE public.page_section_master OWNER TO postgres;

--
-- TOC entry 264 (class 1259 OID 16890)
-- Name: page_section_master_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.page_section_master_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.page_section_master_id_seq OWNER TO postgres;

--
-- TOC entry 5194 (class 0 OID 0)
-- Dependencies: 264
-- Name: page_section_master_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.page_section_master_id_seq OWNED BY public.page_section_master.id;


--
-- TOC entry 243 (class 1259 OID 16788)
-- Name: paragraph; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.paragraph (
    id bigint NOT NULL,
    menu_id bigint NOT NULL,
    page_section_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    meta_title character varying(255) NOT NULL,
    meta_description character varying(255) NOT NULL,
    meta_keywords character varying(255) NOT NULL,
    status smallint NOT NULL,
    hindi_title character varying(255),
    khasi_title character varying(255),
    other_title character varying(255),
    hindi_description character varying(255),
    khasi_description character varying(255),
    other_description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.paragraph OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 16787)
-- Name: paragraph_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.paragraph_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.paragraph_id_seq OWNER TO postgres;

--
-- TOC entry 5195 (class 0 OID 0)
-- Dependencies: 242
-- Name: paragraph_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.paragraph_id_seq OWNED BY public.paragraph.id;


--
-- TOC entry 223 (class 1259 OID 16682)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 16742)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 16741)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 5196 (class 0 OID 0)
-- Dependencies: 232
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 263 (class 1259 OID 16884)
-- Name: role; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role (
    id bigint NOT NULL,
    role_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 16883)
-- Name: role_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.role_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_id_seq OWNER TO postgres;

--
-- TOC entry 5197 (class 0 OID 0)
-- Dependencies: 262
-- Name: role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.role_id_seq OWNED BY public.role.id;


--
-- TOC entry 220 (class 1259 OID 16664)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    role_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16663)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- TOC entry 5198 (class 0 OID 0)
-- Dependencies: 219
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 224 (class 1259 OID 16689)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 16781)
-- Name: slides; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.slides (
    id bigint NOT NULL,
    path character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.slides OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 16780)
-- Name: slides_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.slides_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.slides_id_seq OWNER TO postgres;

--
-- TOC entry 5199 (class 0 OID 0)
-- Dependencies: 240
-- Name: slides_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.slides_id_seq OWNED BY public.slides.id;


--
-- TOC entry 269 (class 1259 OID 16907)
-- Name: theme; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.theme (
    id bigint NOT NULL,
    theme character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.theme OWNER TO postgres;

--
-- TOC entry 268 (class 1259 OID 16906)
-- Name: theme_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.theme_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.theme_id_seq OWNER TO postgres;

--
-- TOC entry 5200 (class 0 OID 0)
-- Dependencies: 268
-- Name: theme_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.theme_id_seq OWNED BY public.theme.id;


--
-- TOC entry 267 (class 1259 OID 16898)
-- Name: theme_master; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.theme_master (
    id bigint NOT NULL,
    theme_name character varying(255) NOT NULL,
    path character varying(255) NOT NULL,
    image character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.theme_master OWNER TO postgres;

--
-- TOC entry 266 (class 1259 OID 16897)
-- Name: theme_master_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.theme_master_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.theme_master_id_seq OWNER TO postgres;

--
-- TOC entry 5201 (class 0 OID 0)
-- Dependencies: 266
-- Name: theme_master_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.theme_master_id_seq OWNED BY public.theme_master.id;


--
-- TOC entry 222 (class 1259 OID 16671)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    role_id smallint NOT NULL,
    is_enabled smallint DEFAULT '1'::smallint NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16670)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5202 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 247 (class 1259 OID 16806)
-- Name: website; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.website (
    id bigint NOT NULL,
    website_name character varying(255) NOT NULL,
    website_department character varying(255) NOT NULL,
    website_short character varying(255) NOT NULL,
    logo character varying(255) NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    hindi_name character varying(255),
    khasi_name character varying(255),
    other_name character varying(255),
    hindi_department character varying(255),
    khasi_department character varying(255),
    other_department character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.website OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 16805)
-- Name: website_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.website_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.website_id_seq OWNER TO postgres;

--
-- TOC entry 5203 (class 0 OID 0)
-- Dependencies: 246
-- Name: website_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.website_id_seq OWNED BY public.website.id;


--
-- TOC entry 4885 (class 2604 OID 16955)
-- Name: archive_news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archive_news ALTER COLUMN id SET DEFAULT nextval('public.archive_news_id_seq'::regclass);


--
-- TOC entry 4884 (class 2604 OID 16919)
-- Name: banner id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.banner ALTER COLUMN id SET DEFAULT nextval('public.banner_id_seq'::regclass);


--
-- TOC entry 4869 (class 2604 OID 16836)
-- Name: card_links id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.card_links ALTER COLUMN id SET DEFAULT nextval('public.card_links_id_seq'::regclass);


--
-- TOC entry 4867 (class 2604 OID 16818)
-- Name: cards id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cards ALTER COLUMN id SET DEFAULT nextval('public.cards_id_seq'::regclass);


--
-- TOC entry 4865 (class 2604 OID 16800)
-- Name: carousel id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carousel ALTER COLUMN id SET DEFAULT nextval('public.carousel_id_seq'::regclass);


--
-- TOC entry 4855 (class 2604 OID 16733)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4877 (class 2604 OID 16876)
-- Name: footer id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.footer ALTER COLUMN id SET DEFAULT nextval('public.footer_id_seq'::regclass);


--
-- TOC entry 4870 (class 2604 OID 16845)
-- Name: gallery id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery ALTER COLUMN id SET DEFAULT nextval('public.gallery_id_seq'::regclass);


--
-- TOC entry 4871 (class 2604 OID 16854)
-- Name: govt_websites id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.govt_websites ALTER COLUMN id SET DEFAULT nextval('public.govt_websites_id_seq'::regclass);


--
-- TOC entry 4862 (class 2604 OID 16775)
-- Name: home id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.home ALTER COLUMN id SET DEFAULT nextval('public.home_id_seq'::regclass);


--
-- TOC entry 4854 (class 2604 OID 16716)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4868 (class 2604 OID 16827)
-- Name: latest_news id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.latest_news ALTER COLUMN id SET DEFAULT nextval('public.latest_news_id_seq'::regclass);


--
-- TOC entry 4859 (class 2604 OID 16764)
-- Name: menu id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu ALTER COLUMN id SET DEFAULT nextval('public.menu_id_seq'::regclass);


--
-- TOC entry 4858 (class 2604 OID 16757)
-- Name: menu_master id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu_master ALTER COLUMN id SET DEFAULT nextval('public.menu_master_id_seq'::regclass);


--
-- TOC entry 4850 (class 2604 OID 16660)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4886 (class 2604 OID 16964)
-- Name: newsletter id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletter ALTER COLUMN id SET DEFAULT nextval('public.newsletter_id_seq'::regclass);


--
-- TOC entry 4887 (class 2604 OID 16973)
-- Name: newsletterdetails id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletterdetails ALTER COLUMN id SET DEFAULT nextval('public.newslettersdetails_id_seq'::regclass);


--
-- TOC entry 4874 (class 2604 OID 16865)
-- Name: page_section id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.page_section ALTER COLUMN id SET DEFAULT nextval('public.page_section_id_seq'::regclass);


--
-- TOC entry 4881 (class 2604 OID 16894)
-- Name: page_section_master id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.page_section_master ALTER COLUMN id SET DEFAULT nextval('public.page_section_master_id_seq'::regclass);


--
-- TOC entry 4864 (class 2604 OID 16791)
-- Name: paragraph id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paragraph ALTER COLUMN id SET DEFAULT nextval('public.paragraph_id_seq'::regclass);


--
-- TOC entry 4857 (class 2604 OID 16745)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4880 (class 2604 OID 16887)
-- Name: role id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role ALTER COLUMN id SET DEFAULT nextval('public.role_id_seq'::regclass);


--
-- TOC entry 4851 (class 2604 OID 16667)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 4863 (class 2604 OID 16784)
-- Name: slides id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slides ALTER COLUMN id SET DEFAULT nextval('public.slides_id_seq'::regclass);


--
-- TOC entry 4883 (class 2604 OID 16910)
-- Name: theme id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.theme ALTER COLUMN id SET DEFAULT nextval('public.theme_id_seq'::regclass);


--
-- TOC entry 4882 (class 2604 OID 16901)
-- Name: theme_master id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.theme_master ALTER COLUMN id SET DEFAULT nextval('public.theme_master_id_seq'::regclass);


--
-- TOC entry 4852 (class 2604 OID 16674)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4866 (class 2604 OID 16809)
-- Name: website id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website ALTER COLUMN id SET DEFAULT nextval('public.website_id_seq'::regclass);


--
-- TOC entry 5166 (class 0 OID 16952)
-- Dependencies: 273
-- Data for Name: archive_news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.archive_news (id, file, link, type, title, "order", status, hindi_title, khasi_title, other_title, created_at, updated_at) FROM stdin;
1		https://google.com	link	Test Link	1	t	जोड़ना\n	Khasi test	\N	2024-10-14 05:16:09	2024-11-14 05:16:09
4	news/1731561454-CMS Progress Report.pdf	\N	file	Pdf old News	1	t	पीडीएफ नवीनतम समाचार	\N	\N	2024-09-14 05:17:34	2024-09-14 05:17:34
5	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
7	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
9	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
11	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
13	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
15	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
17	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	file	Weekly News Letter	1	t	साप्ताहिक समाचार पत्र\n	\N	\N	2024-11-14 07:59:10	2024-11-14 07:59:10
22		https://google.com	link	November Month Link	1	t	नवंबर माह का लिंक\n	\N	\N	2024-11-22 10:36:57	2024-11-22 10:36:57
\.


--
-- TOC entry 5164 (class 0 OID 16916)
-- Dependencies: 271
-- Data for Name: banner; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.banner (id, image, menu_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5118 (class 0 OID 16698)
-- Dependencies: 225
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
af8ae45556ca1de4bbb35b13a4fde47e:timer	i:1733123155;	1733123155
af8ae45556ca1de4bbb35b13a4fde47e	i:1;	1733123155
2be2b0608bcc1788003fd9525424b40c:timer	i:1733374911;	1733374911
2be2b0608bcc1788003fd9525424b40c	i:1;	1733374911
\.


--
-- TOC entry 5119 (class 0 OID 16705)
-- Dependencies: 226
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5146 (class 0 OID 16833)
-- Dependencies: 253
-- Data for Name: card_links; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.card_links (id, card_id, link_name, link, file, "order", status, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5142 (class 0 OID 16815)
-- Dependencies: 249
-- Data for Name: cards; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cards (id, menu_id, page_section_id, card_title, card_description, image, more_link, "order", status, hindi_title, khasi_title, other_title, hindi_description, khasi_description, other_description, created_at, updated_at) FROM stdin;
10	1	1	Success Stories	Success Stories	cards/1731655916-hmpge-1.jpg	https://google.cin	2	1	\N	\N	\N	\N	\N	\N	2024-11-15 07:31:56	2024-11-15 07:31:56
11	1	2	Project	Project	cards/1731656244-hmpge-2.jpg	https://google.cin	1	1	\N	\N	\N	\N	\N	\N	2024-11-15 07:37:24	2024-11-15 07:37:24
12	1	3	Documentation	Documentation	cards/1731656328-hmpge-3.jpg	https://google.cin	3	1	\N	\N	\N	\N	\N	\N	2024-11-15 07:38:48	2024-11-15 07:38:48
13	1	4	Media	Media	cards/1731666301-hmpge-4.jpg	https://google.cin	4	1	\N	\N	\N	\N	\N	\N	2024-11-15 10:25:01	2024-11-15 10:25:01
14	1	5	Progress Indicators	Progress Indicators	cards/1731925611-hmpge-5.jpg	https://google.cin	5	1	\N	\N	\N	\N	\N	\N	2024-11-18 10:26:51	2024-11-18 10:26:51
15	1	6	Frequently Asked Questions	Frequently Asked Questions	cards/1731925672-hmpge-6.jpg	https://google.cin	6	1	\N	\N	\N	\N	\N	\N	2024-11-18 10:27:52	2024-11-18 10:27:52
\.


--
-- TOC entry 5138 (class 0 OID 16797)
-- Dependencies: 245
-- Data for Name: carousel; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.carousel (id, image, type, link, title, alt, "order", status, hindi_title, khasi_title, other_title, created_at, updated_at) FROM stdin;
1	slides/1.png	Slider	slides/1.png	Main Carousel	Alt Text	1	1	\N	\N	\N	\N	\N
2	slides/2.png	Slider	slides/2.png	Second Carousel	Alt Text	2	1	\N	\N	\N	\N	\N
3	slides/3.png	Slider	slides/3.png	Third Carousel	Alt Text	3	3	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5124 (class 0 OID 16730)
-- Dependencies: 231
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5154 (class 0 OID 16873)
-- Dependencies: 261
-- Data for Name: footer; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.footer (id, type, content, link, "order", created_at, updated_at, link_title) FROM stdin;
3	logo	footer/footer1.jpg	\N	3	\N	\N	\N
2	link	\N	https://www.youtube.com/channel/UChef6rIXCLwBWWpySKHHezg	3	\N	\N	youtube
4	link	\N	https://www.facebook.com/msrlsmeghalaya	1	\N	\N	facebook
5	link	\N	https://x.com/i/flow/login?redirect_after_login=%2Fmeg_srlm	2	\N	\N	twitter
6	link	\N	https://www.instagram.com/msrls_meghalaya/	4	\N	\N	instagram
1	text	Copyright © 2024- All Rights Reserved - Official website of Meghalaya State Rural Livelihoods Society, Government of Meghalaya, India\nNote: Content on this website is published and managed by Meghalaya State Rural Livelihoods Society, Government of Meghalaya\nFor any query regarding this website, please contact the web information manager\nE-mail ID: nrlm[dash]meg[at]nic[dot]in	https://msrls.nic.in/accessibility-statement	1	\N	\N	Accessibility Statement
7	logo	footer/footer2.jpg	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5148 (class 0 OID 16842)
-- Dependencies: 255
-- Data for Name: gallery; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gallery (id, parent_id, gallery_name, gallery_image, gallery_description, link, meta_title, meta_description, meta_keywords, "order", status, hindi_name, khasi_name, other_name, hindi_description, khasi_description, other_description, created_at, updated_at) FROM stdin;
9	2	Products of SHGs at SARAS fair - 2	msrls/gallery/qRAT3aUhtdL6BkUN1djI9H8ZVffTZTn4oolAzcVm.jpg	Products of SHGs at SARAS fair - 2	msrls/gallery/qRAT3aUhtdL6BkUN1djI9H8ZVffTZTn4oolAzcVm.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 06:08:47	2024-11-22 06:08:47
10	2	Products of SHGs at SARAS fair - 3	msrls/gallery/KK8EqAigdEkd1h0uNjT75bU608QVnKW7XRcVgTPj.jpg	Products of SHGs at SARAS fair - 3	msrls/gallery/KK8EqAigdEkd1h0uNjT75bU608QVnKW7XRcVgTPj.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 07:19:16	2024-11-22 07:19:16
11	2	Products of SHGs at SARAS fair - 4	msrls/gallery/IZVCJHDcma63TSjaIWYXd5x481kteHHdpBXh7eQY.jpg	Products of SHGs at SARAS fair - 4	msrls/gallery/IZVCJHDcma63TSjaIWYXd5x481kteHHdpBXh7eQY.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 07:19:52	2024-11-22 07:19:52
12	2	SHG members from Laskein Block Participating at SARAS Fair New Delhi.	msrls/gallery/x837KlCcOPEJPoy4KKWv9kIrmeJTfO4364PiI5ra.jpg	SHG members from Laskein Block Participating at SARAS Fair New Delhi.	msrls/gallery/x837KlCcOPEJPoy4KKWv9kIrmeJTfO4364PiI5ra.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 07:23:14	2024-11-22 07:23:14
13	2	State Level Debriefing at SIRD, Nongsder.	msrls/gallery/PqO0sttA3E8eeDL56iKAFpdlbgcc8YunLJpEv7CN.jpg	State Level Debriefing at SIRD, Nongsder.	msrls/gallery/PqO0sttA3E8eeDL56iKAFpdlbgcc8YunLJpEv7CN.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 09:44:33	2024-11-22 09:44:33
14	2	Introductory by CEO MSRLS at SARAS fair.	msrls/gallery/D7sRaWLMVu3gCQqXD2Wkeu1JTccf9xrG8wAUZ8aB.jpg	Introductory by CEO MSRLS at SARAS fair.	msrls/gallery/D7sRaWLMVu3gCQqXD2Wkeu1JTccf9xrG8wAUZ8aB.jpg	test gallery	test gallery	msrls gallery	1	1	\N	\N	\N	\N	\N	\N	2024-11-22 09:45:14	2024-11-22 09:45:14
\.


--
-- TOC entry 5150 (class 0 OID 16851)
-- Dependencies: 257
-- Data for Name: govt_websites; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.govt_websites (id, image, link, "order", status, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5132 (class 0 OID 16772)
-- Dependencies: 239
-- Data for Name: home; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.home (id, title, description, more_link, image, image_decsription, hindi_title, khasi_title, other_title, hindi_description, khasi_description, other_description, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5122 (class 0 OID 16722)
-- Dependencies: 229
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5121 (class 0 OID 16713)
-- Dependencies: 228
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5144 (class 0 OID 16824)
-- Dependencies: 251
-- Data for Name: latest_news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.latest_news (id, title, type, file, link, meta_keywords, "order", status, hindi_title, khasi_title, other_title, created_at, updated_at) FROM stdin;
3	Weekly News Letter	file	news/1731571149-msrls-weekly-newsletter-9th-15th.pdf	\N	\N	1	1	साप्ताहिक समाचार पत्र\n	\N	\N	2024-12-02 07:59:10	2024-12-02 07:59:10
6	December Link Msrls	link	\N	https://msrls.nic.in/	test	3	1	नवंबर लिंक एमएसआरएलएस\n	\N	\N	2024-12-02 07:59:10	2024-12-02 07:59:10
\.


--
-- TOC entry 5130 (class 0 OID 16761)
-- Dependencies: 237
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.menu (id, menu_name, menu_master, "order", parent, status, hindi_name, khasi_name, other_name) FROM stdin;
1	Home	1	1	0	1	घर	Rympei	\N
6	Test Setting	1	2	4	1	परीक्षण सेटिंग्स	\N	\N
8	Test Setting 2	1	1	4	1	परीक्षण सेटिंग 2\n	\N	\N
11	Gallery	1	2	0	1	गैलरी	Test Khasi	\N
4	Settings	1	6	0	1	सेटिंग्स	\N	\N
7	Services	1	5	0	1	सेवाएं	\N	\N
2	About Us	1	3	0	1	हमारे बारे में	\N	\N
3	Contact Us	1	7	0	1	हमसे संपर्क करें\n	\N	\N
12	Who's Who	1	8	2	1	\N	\N	\N
9	Vission	1	9	2	1	दृष्टि	\N	\N
10	Scope	1	10	2	1	दायरा	\N	\N
13	Programmes & Schemes	1	11	2	1	Programmes & Schemes	Programmes & Schemes	\N
14	Notice Board	1	12	0	1	\N	\N	\N
15	News Letter	1	13	14	1	\N	\N	\N
\.


--
-- TOC entry 5128 (class 0 OID 16754)
-- Dependencies: 235
-- Data for Name: menu_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.menu_master (id, menu_name) FROM stdin;
1	Main Menu
2	Left Menu
3	Right Menu
\.


--
-- TOC entry 5111 (class 0 OID 16657)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0000_01_01_000000_create_role_table	1
2	0001_01_01_000000_create_users_table	1
3	0001_01_01_000001_create_cache_table	1
4	0001_01_01_000002_create_jobs_table	1
5	2024_05_23_100235_create_personal_access_tokens_table	1
6	2024_05_29_104743_menu_master	1
7	2024_05_29_113106_menu_table	1
8	2024_05_31_074310_add_two_factor_columns_to_users_table	1
9	2024_06_03_105059_create_home_table	1
10	2024_06_03_111744_slides_table-NONEED	1
11	2024_06_04_053441_create_paragraph_table	1
12	2024_06_04_063237_create_carousel_table	1
13	2024_06_04_070912_website	1
14	2024_06_04_103432_create_cards_table	1
15	2024_06_04_103522_create_latest_news_table	1
16	2024_06_04_103555_create_card_links_table	1
17	2024_06_04_103721_create_gallery_table	1
18	2024_06_04_104019_create_govt_websites_table	1
19	2024_06_05_101649_create_page_section_table	1
20	2024_06_05_113356_create_footer_table	1
21	2024_06_10_094419_create_role_table	1
22	2024_07_10_095239_create_page_section_master	1
23	2024_07_15_111520_create_theme_master_table	1
24	2024_07_15_111653_create_theme_table	1
25	2024_08_20_112410_add_icon_to_page_section_master_table	1
26	2024_08_21_102840_drop_column_from_page_section_table	1
27	2024_08_23_104742_create_banner_table	1
28	2024_11_22_095754_create_archive_news_table	2
29	2024_12_04_052010_create_newsletter_table	3
30	2024_12_04_052101_create_newsletterdetails_table	3
\.


--
-- TOC entry 5168 (class 0 OID 16961)
-- Dependencies: 275
-- Data for Name: newsletter; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.newsletter (id, menu_id, page_section_id, english_title, hindi_title, khasi_title, date, created_at, updated_at) FROM stdin;
3	1	14	Weekly Newsletter 	साप्ताहिक समाचार पत्र\n	\N	2024-11-01	\N	\N
6	1	14	Test1	\N	\N	2024-11-01	\N	\N
8	1	14	Test 2	\N	\N	2024-11-01	\N	\N
9	1	14	Test3	\N	\N	2024-11-01	\N	\N
4	1	14	International Women's Day 2024  	अंतर्राष्ट्रीय महिला दिवस समारोह 2024	\N	2024-03-06	\N	\N
\.


--
-- TOC entry 5170 (class 0 OID 16970)
-- Dependencies: 277
-- Data for Name: newsletterdetails; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.newsletterdetails (id, newsletterid, menu_id, page_section_id, english_title, hindi_title, khasi_title, fromdate, todate, date, filepath, created_at, updated_at) FROM stdin;
1	3	1	1	Weekly Newsletter (1st-9th November 2024)	Weekly Newsletter (1st-9th November 2024)	Weekly Newsletter (1st-9th November 2024)	2024-11-01	2024-11-09	2024-11-01	newsletter/november-weekly-newsletter-1st-9th Nov.pdf	\N	\N
2	3	1	1	Weekly Newsletter (9th-15th November 2024)	Weekly Newsletter (9th-15th November 2024)	Weekly Newsletter (9th-15th November 2024)	2024-11-09	2024-11-15	2024-11-09	newsletter/november-weekly-newsletter-9th-15th Nov.pdf	\N	\N
\.


--
-- TOC entry 5152 (class 0 OID 16862)
-- Dependencies: 259
-- Data for Name: page_section; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.page_section (id, menu_id, page_section_name, page_section_id, "order", status) FROM stdin;
1	1	Carousel	1	1	1
2	1	Latest News	2	1	1
3	1	Home Page Paragraph	3	1	1
4	2	Paragraph	7	1	1
5	1	Cards	4	1	1
6	4	Carousel	1	1	1
7	1	Paragraph	7	1	1
8	11	Gallery	5	1	1
\.


--
-- TOC entry 5158 (class 0 OID 16891)
-- Dependencies: 265
-- Data for Name: page_section_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.page_section_master (id, page_section_name, icon) FROM stdin;
1	Carousel	logo/slider.png
2	Latest News	logo/news.png
3	Home Page Paragraph	logo/website.png
4	Cards	logo/description.png
5	Gallery	logo/gallery.png
6	Notifications	logo/notifications.png
7	Paragraph	logo/description.png
8	Banner	logo/table.png
\.


--
-- TOC entry 5136 (class 0 OID 16788)
-- Dependencies: 243
-- Data for Name: paragraph; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.paragraph (id, menu_id, page_section_id, title, description, meta_title, meta_description, meta_keywords, status, hindi_title, khasi_title, other_title, hindi_description, khasi_description, other_description, created_at, updated_at) FROM stdin;
1	2	7	4th Meghalaya Saras Aajeevika Fair 2023 (Saras Towards Growth & Equity)	Welcome to the  4th Meghalaya Saras Aajeevika Fair 2023 (Saras Towards Growth & Equity)! Join us from May 22nd to May 31st for an extraordinary showcase of diverse crafts, exquisite handlooms, organic products, and authentic flavors. Immerse yourself in a celebration of rural entrepreneurship, sustainability, and cultural heritage at this vibrant fair.	Welcome to the  4th Meghalaya Saras Aajeevika Fair 2023 (Saras Towards Growth & Equity)	Meta About us description	about us	1	चौथा मेघालय सरस आजीविका मेला 2023 (सारस विकास और समानता की ओर)	Testing Khasi Title for 4th Meghalaya Saras Aajeevika Fair 2023 (Saras Towards Growth & Equity)\n	\N	चौथे मेघालय सरस आजीविका मेला 2023 (सारस विकास और समानता की ओर) में आपका स्वागत है! विविध शिल्प, उत्तम हथकरघा, जैविक उत्पादों और प्रामाणिक स्वादों के असाधारण प्रदर्शन के लिए ।	\N	\N	\N	\N
2	7	8	The Meghalaya State Rural Livelihoods Society	As per the guidelines of NRLM, the Government of Meghalaya has formed a state society in the name – Meghalaya State Rural Livelihoods Society (MSRLS). The society is registered under the Meghalaya Society Registration Act XII of 1983 and was designated as the Nodal Agency for implementing NRLM in the state. The basic purpose of forming this society is to put in place a dedicated and sensitive support structure from the State level down to the sub-district level which will focus on building strong and self-managed institution of the poor at different levels. This will provide the poor a platform for collective action based on self-help and mutual cooperation, build linkages with mainstream financial institutions and Government departments to address the multi-faceted dimensions of rural poverty.	The Meghalaya State Rural Livelihoods Society	The Meghalaya State Rural Livelihoods Society	The Meghalaya State Rural Livelihoods Society	1	मेघालय राज्य ग्रामीण आजीविका सोसायटी\n	\N	\N	एनआरएलएम के दिशानिर्देशों के अनुसार, मेघालय सरकार ने मेघालय राज्य ग्रामीण आजीविका सोसायटी (एमएसआरएलएस) नाम से एक राज्य सोसायटी का गठन किया है। सोसायटी 1983 के मेघालय सोसायटी पंजीकरण अधिनियम XII के तहत पंजीकृत है।	\N	\N	\N	\N
\.


--
-- TOC entry 5116 (class 0 OID 16682)
-- Dependencies: 223
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5126 (class 0 OID 16742)
-- Dependencies: 233
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5156 (class 0 OID 16884)
-- Dependencies: 263
-- Data for Name: role; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role (id, role_name, created_at, updated_at) FROM stdin;
1	SuperAdmin	\N	\N
2	Admin	\N	\N
3	Publisher	\N	\N
\.


--
-- TOC entry 5113 (class 0 OID 16664)
-- Dependencies: 220
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, role_name, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5117 (class 0 OID 16689)
-- Dependencies: 224
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
C7MM4NDVLAjBnH2tgVTKr25zIVuBHcjxTRWFwUOV	2	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36	YTo1OntzOjY6Il90b2tlbiI7czo0MDoiT1RZbWlmM3NidzRudXl3bkxXeDZpS1RIT0U4NE9pUDIxUE5FcHZoeCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0ODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Fzc2V0cy9pbWcvbG9nby9sb2dvLTEucG5nIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hc3NldHMvaW1nL2xvZ28vbG9nby0xLnBuZyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==	1733391609
\.


--
-- TOC entry 5134 (class 0 OID 16781)
-- Dependencies: 241
-- Data for Name: slides; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.slides (id, path, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5162 (class 0 OID 16907)
-- Dependencies: 269
-- Data for Name: theme; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.theme (id, theme, created_at, updated_at) FROM stdin;
1	5	\N	2024-12-02 06:13:49
\.


--
-- TOC entry 5160 (class 0 OID 16898)
-- Dependencies: 267
-- Data for Name: theme_master; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.theme_master (id, theme_name, path, image, created_at, updated_at) FROM stdin;
1	Theme 1	smb	smb.png	\N	\N
2	Theme 2	theme2	smb.png	\N	\N
3	Theme 3	theme3	smb.png	\N	\N
4	Theme 4	theme4	smb.png	\N	\N
5	Mslrs	msrls	mslrs.png	\N	\N
\.


--
-- TOC entry 5115 (class 0 OID 16671)
-- Dependencies: 222
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, role_id, is_enabled, remember_token, created_at, updated_at, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at) FROM stdin;
1	Lucy	lucy.manisha@nic.in	\N	$2y$12$FG6GvtEQAL3jWHdsP0KtRORUy3mnOHpSv.BWuFxT4xRNsCrE2JPc.	1	1	\N	\N	\N	\N	\N	\N
2	Admin	admin@mail.com	\N	$2y$12$YOsTyhWfe/h.6nQe8tfWTOMO3XnxlsNnot7FOVswJIUrygShPuNq.	2	1	\N	\N	\N	\N	\N	\N
3	Dev	dev@gmail.com	\N	$2y$12$hS0Vp3CsXtQRUVO.gH14IuJPZ9QAYqnZ9MIw6rdAWEqxMsp4lfpmm	3	1	\N	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5140 (class 0 OID 16806)
-- Dependencies: 247
-- Data for Name: website; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.website (id, website_name, website_department, website_short, logo, title, description, hindi_name, khasi_name, other_name, hindi_department, khasi_department, other_department, created_at, updated_at) FROM stdin;
1	Website Name	Website Department	WB	msrls/header/msrlsheader.png	Test		\N	\N	\N	\N	\N	\N	\N	2024-11-11 06:10:14
\.


--
-- TOC entry 5204 (class 0 OID 0)
-- Dependencies: 272
-- Name: archive_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.archive_news_id_seq', 22, true);


--
-- TOC entry 5205 (class 0 OID 0)
-- Dependencies: 270
-- Name: banner_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.banner_id_seq', 1, false);


--
-- TOC entry 5206 (class 0 OID 0)
-- Dependencies: 252
-- Name: card_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.card_links_id_seq', 1, false);


--
-- TOC entry 5207 (class 0 OID 0)
-- Dependencies: 248
-- Name: cards_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cards_id_seq', 15, true);


--
-- TOC entry 5208 (class 0 OID 0)
-- Dependencies: 244
-- Name: carousel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.carousel_id_seq', 2, true);


--
-- TOC entry 5209 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5210 (class 0 OID 0)
-- Dependencies: 260
-- Name: footer_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.footer_id_seq', 7, true);


--
-- TOC entry 5211 (class 0 OID 0)
-- Dependencies: 254
-- Name: gallery_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gallery_id_seq', 14, true);


--
-- TOC entry 5212 (class 0 OID 0)
-- Dependencies: 256
-- Name: govt_websites_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.govt_websites_id_seq', 1, false);


--
-- TOC entry 5213 (class 0 OID 0)
-- Dependencies: 238
-- Name: home_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.home_id_seq', 1, false);


--
-- TOC entry 5214 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5215 (class 0 OID 0)
-- Dependencies: 250
-- Name: latest_news_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.latest_news_id_seq', 7, true);


--
-- TOC entry 5216 (class 0 OID 0)
-- Dependencies: 236
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menu_id_seq', 15, true);


--
-- TOC entry 5217 (class 0 OID 0)
-- Dependencies: 234
-- Name: menu_master_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.menu_master_id_seq', 1, false);


--
-- TOC entry 5218 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 30, true);


--
-- TOC entry 5219 (class 0 OID 0)
-- Dependencies: 274
-- Name: newsletter_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.newsletter_id_seq', 9, true);


--
-- TOC entry 5220 (class 0 OID 0)
-- Dependencies: 276
-- Name: newslettersdetails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.newslettersdetails_id_seq', 2, true);


--
-- TOC entry 5221 (class 0 OID 0)
-- Dependencies: 258
-- Name: page_section_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.page_section_id_seq', 8, true);


--
-- TOC entry 5222 (class 0 OID 0)
-- Dependencies: 264
-- Name: page_section_master_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.page_section_master_id_seq', 1, false);


--
-- TOC entry 5223 (class 0 OID 0)
-- Dependencies: 242
-- Name: paragraph_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.paragraph_id_seq', 3, true);


--
-- TOC entry 5224 (class 0 OID 0)
-- Dependencies: 232
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 5225 (class 0 OID 0)
-- Dependencies: 262
-- Name: role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.role_id_seq', 3, true);


--
-- TOC entry 5226 (class 0 OID 0)
-- Dependencies: 219
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 1, false);


--
-- TOC entry 5227 (class 0 OID 0)
-- Dependencies: 240
-- Name: slides_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.slides_id_seq', 1, false);


--
-- TOC entry 5228 (class 0 OID 0)
-- Dependencies: 268
-- Name: theme_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.theme_id_seq', 1, true);


--
-- TOC entry 5229 (class 0 OID 0)
-- Dependencies: 266
-- Name: theme_master_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.theme_master_id_seq', 5, true);


--
-- TOC entry 5230 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- TOC entry 5231 (class 0 OID 0)
-- Dependencies: 246
-- Name: website_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.website_id_seq', 1, true);


--
-- TOC entry 4960 (class 2606 OID 16959)
-- Name: archive_news archive_news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.archive_news
    ADD CONSTRAINT archive_news_pkey PRIMARY KEY (id);


--
-- TOC entry 4958 (class 2606 OID 16921)
-- Name: banner banner_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.banner
    ADD CONSTRAINT banner_pkey PRIMARY KEY (id);


--
-- TOC entry 4906 (class 2606 OID 16711)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 4904 (class 2606 OID 16704)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 4940 (class 2606 OID 16840)
-- Name: card_links card_links_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.card_links
    ADD CONSTRAINT card_links_pkey PRIMARY KEY (id);


--
-- TOC entry 4936 (class 2606 OID 16822)
-- Name: cards cards_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cards
    ADD CONSTRAINT cards_pkey PRIMARY KEY (id);


--
-- TOC entry 4932 (class 2606 OID 16804)
-- Name: carousel carousel_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.carousel
    ADD CONSTRAINT carousel_pkey PRIMARY KEY (id);


--
-- TOC entry 4913 (class 2606 OID 16738)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4915 (class 2606 OID 16740)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4948 (class 2606 OID 16882)
-- Name: footer footer_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.footer
    ADD CONSTRAINT footer_pkey PRIMARY KEY (id);


--
-- TOC entry 4942 (class 2606 OID 16849)
-- Name: gallery gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gallery
    ADD CONSTRAINT gallery_pkey PRIMARY KEY (id);


--
-- TOC entry 4944 (class 2606 OID 16860)
-- Name: govt_websites govt_websites_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.govt_websites
    ADD CONSTRAINT govt_websites_pkey PRIMARY KEY (id);


--
-- TOC entry 4926 (class 2606 OID 16779)
-- Name: home home_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.home
    ADD CONSTRAINT home_pkey PRIMARY KEY (id);


--
-- TOC entry 4911 (class 2606 OID 16728)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 4908 (class 2606 OID 16720)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4938 (class 2606 OID 16831)
-- Name: latest_news latest_news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.latest_news
    ADD CONSTRAINT latest_news_pkey PRIMARY KEY (id);


--
-- TOC entry 4922 (class 2606 OID 16759)
-- Name: menu_master menu_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu_master
    ADD CONSTRAINT menu_master_pkey PRIMARY KEY (id);


--
-- TOC entry 4924 (class 2606 OID 16770)
-- Name: menu menu_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.menu
    ADD CONSTRAINT menu_pkey PRIMARY KEY (id);


--
-- TOC entry 4890 (class 2606 OID 16662)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4962 (class 2606 OID 16968)
-- Name: newsletter newsletter_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletter
    ADD CONSTRAINT newsletter_pkey PRIMARY KEY (id);


--
-- TOC entry 4964 (class 2606 OID 16977)
-- Name: newsletterdetails newslettersdetails_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.newsletterdetails
    ADD CONSTRAINT newslettersdetails_pkey PRIMARY KEY (id);


--
-- TOC entry 4952 (class 2606 OID 16896)
-- Name: page_section_master page_section_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.page_section_master
    ADD CONSTRAINT page_section_master_pkey PRIMARY KEY (id);


--
-- TOC entry 4946 (class 2606 OID 16871)
-- Name: page_section page_section_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.page_section
    ADD CONSTRAINT page_section_pkey PRIMARY KEY (id);


--
-- TOC entry 4930 (class 2606 OID 16795)
-- Name: paragraph paragraph_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paragraph
    ADD CONSTRAINT paragraph_pkey PRIMARY KEY (id);


--
-- TOC entry 4898 (class 2606 OID 16688)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 4917 (class 2606 OID 16749)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4919 (class 2606 OID 16752)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4950 (class 2606 OID 16889)
-- Name: role role_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role
    ADD CONSTRAINT role_pkey PRIMARY KEY (id);


--
-- TOC entry 4892 (class 2606 OID 16669)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 4901 (class 2606 OID 16695)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 4928 (class 2606 OID 16786)
-- Name: slides slides_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.slides
    ADD CONSTRAINT slides_pkey PRIMARY KEY (id);


--
-- TOC entry 4954 (class 2606 OID 16905)
-- Name: theme_master theme_master_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.theme_master
    ADD CONSTRAINT theme_master_pkey PRIMARY KEY (id);


--
-- TOC entry 4956 (class 2606 OID 16912)
-- Name: theme theme_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.theme
    ADD CONSTRAINT theme_pkey PRIMARY KEY (id);


--
-- TOC entry 4894 (class 2606 OID 16681)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4896 (class 2606 OID 16679)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4934 (class 2606 OID 16813)
-- Name: website website_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website
    ADD CONSTRAINT website_pkey PRIMARY KEY (id);


--
-- TOC entry 4909 (class 1259 OID 16721)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4920 (class 1259 OID 16750)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4899 (class 1259 OID 16697)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 4902 (class 1259 OID 16696)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


-- Completed on 2024-12-05 15:33:18

--
-- PostgreSQL database dump complete
--

