import{c as s}from"./Input.vue_vue_type_script_setup_true_lang-DZpTo509.js";import{d as n,q as a,c,o as i,n as l,u as m}from"./app-DxcfcLTv.js";const u=["innerHTML"],h=n({__name:"VideoEmbed",props:{url:{},class:{}},setup(o){const r=o,t=a(()=>{const e=r.url.trim();return/\.(mp4|webm|ogg|m3u8|mov)$/i.test(e)?`<video
                    style="width: 100%"
                    height="360"
                    controls
                >
                    <source src="${e}">
                    Your browser does not support the video tag.
                </video>`:`<iframe
                src="${e.replace("watch?v=","embed/")}"
                style="width: 100%"
                height="360"
                frameborder="0"
                allow="autoplay; encrypted-media; picture-in-picture"
                allowfullscreen>
            </iframe>`});return(e,d)=>(i(),c("div",{innerHTML:t.value,class:l(m(s)("",r.class))},null,10,u))}});export{h as _};
