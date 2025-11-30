"use client";
import { useState } from "react";
import React from "react";
import { motion} from "framer-motion";
import {Eye,EyeOff} from "lucide-react";;
import {useRouter} from "next/navigation"
type LoginFormProps = {
  ifCloseAction: () => void;
  ifSwitchAction: (goTo: "registerForm" | "loginForm") => void;
};
export default function LoginForm({ifCloseAction,ifSwitchAction}:LoginFormProps) {
  const router=useRouter();
  const[activeForm,setActiveForm]=useState<"registerForm" | "loginForm">("loginForm");
  const[email,setEmail]=useState("");
  const[password,setPassword]=useState("");
  const[showPassword,setShowPassword]=useState(false);
  const[errorEmail,setErrorEmail]=useState<string|null>(null);
  const[errorPassword,setErrorPassword]=useState<string|null>(null)
  const[errorMess,setErrorMess]=useState<string|null>(null);
  const[succesMess,setSuccesMess]=useState<string|null>(null)
  const[isLoading,setIsLoading]=useState(false);
  
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  function validate() {
    let valid=true;
    if(!emailRegex.test(email)) {
      setErrorEmail("Podaj poprawny adres email");
      valid = false;
    }  else {
    setErrorEmail(null);
    }
    if(password.trim().length<6){
      setErrorPassword("Hasło musi posiadać conajmniej 6 znaków");
      valid=false
    } else {
      setErrorPassword(null);
    }
    return valid;
  };
  async function validateF(event:React.FormEvent) {
    event.preventDefault();
    setSuccesMess(null)
    if(!validate()){ return;}

    try{
      setIsLoading(true);
      const answer = await fetch("http://localhost/glimzyskins/backend/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email,password }),
      });
      const data= await answer.json();

      if(!data.success) {
        setErrorMess(data.message|| "Logowanie nieudane");
        return;
      }
      localStorage.setItem("user",JSON.stringify(data.user));
      setErrorMess(null);
      setSuccesMess("Zalogowano pomyślnie");

      setEmail("");
      setPassword("");

      setTimeout(()=>{
        router.push("/dashboardCases");
      },1500)
    } catch(error:unknown) {
      if(error instanceof Error) {
        setErrorMess("Błąd sieci" + error.message);
      } else{
        setErrorMess("Nie udało się połączyć z serwerem");
      }
        return;
    } finally {
      setIsLoading(false);
    }
  };

  return (
      <div className="flex fixed inset-0 bg-black/70 justify-center z-50">

        <div className="flex justify-center items-center">

          <form  onClick={(event)=>{event.stopPropagation()}} onSubmit={validateF} className=" relative flex flex-col gap-1 w-105 h-105 bg-black p-8 text-gray-300 rounded-sm border border-neutral-800">

              <button type="button"onClick={ifCloseAction} className="absolute flex justify-center top-3 right-2 text-white  w-9 h-9 transition-all duration-200 hover-bg-red  hover:bg-neutral-800 border-none rounded-sm text-2xl focus:outline-none">&times;</button>

              <h1 className="text-amber-400 font-extrabold mb-2 text-3xl text-center tracking-wide">GlimzySkins</h1>

              <div className="flex justify-center gap-10 mb-3 relative border-b-2 pb-3 border-neutral-800">

                <button type="button" onClick={()=>{setActiveForm("loginForm"); ifSwitchAction("loginForm")}} className={`relative font-semibold ${ activeForm=="loginForm" && "text-amber-400"} `}>Zaloguj się {activeForm=="loginForm" &&(<motion.div key="loginForm-underline" className="absolute left-0 bottom-0 h-0.5 bg-amber-400" initial={{width:0}} animate={{width:"100%"}}/>)}</button>

                <button type="button" onClick={()=>{setActiveForm("registerForm"); ifSwitchAction("registerForm")}} className="font-semibold p-2 hover:bg-neutral-900 rounded-md">Zarejestruj się</button>

              </div>

              <label htmlFor="email" className="mt-[9px]">E-mail</label>
              <div className="relative">

                <input type="text"value={email} onChange={(event)=>{setEmail(event.target.value)}} placeholder="np: glimzyskins@gmail.com" className={`p-2 outline-none focus:ring-2 w-88.5 focus:ring-amber-300 bg-neutral-900 rounded-md border border-neutral-800 ${errorEmail ? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`}/>
              
                <div className="min-h-3">
                  {errorEmail && <p className="text-xs text-red-500">{errorEmail}</p>}
                </div>

              </div>

              <label htmlFor="password">Hasło</label>
              <div className="relative">

                <input type={showPassword?"text":"password"} value={password} onChange={(event)=>{setPassword(event.target.value)}} placeholder="np: ToHaslo123!" className={`p-2 focus:ring-2 focus:ring-amber-300 w-88.5 outline-none bg-neutral-900 rounded-sm border border-neutral-800 ${errorPassword? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`}/>
              
                <div className="min-h-3">
                  {errorPassword&&<p className="text-xs text-red-500">{errorPassword}</p>}
                </div>

                <button type="button" className="absolute top-2.5 right-5" onClick={()=>{setShowPassword((p)=>!p)}}>{showPassword?(<EyeOff size={22} strokeWidth={1.8} />):<Eye size={22} strokeWidth={1.8} />}</button>

              </div>

              {errorMess&&(<p className="text-red-500 text-sm mt-4 text-center whitespace-pre-line">{errorMess}</p>)} 
              {succesMess&&(<p className="text-green-500 text-sm mt-4 text-center" >{succesMess}</p>)}

              <button type="submit" disabled={isLoading} className={`mt-6 font-semibold rounded-md py-2.5 text-white ${isLoading ? "bg-amber-600/60" : "bg-amber-500 hover:bg-amber-600"}`}>{isLoading ? "Loading..." : "Zaloguj się"}</button>
         
          </form>
        </div>
      </div>
         
         

  );
}