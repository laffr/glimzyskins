"use client";
import React from "react";
import { useState } from "react";
import { motion } from "framer-motion";
import {Eye,EyeOff} from "lucide-react";
import { useRouter } from "next/navigation";
type RegisterFormProps = {
  ifCloseAction: () => void;
  ifSwitchAction: (goTo: "registerForm"|"loginForm") => void;
};

export default function RegisterForm({ifCloseAction,ifSwitchAction}:RegisterFormProps) {
  const router=useRouter();
  const[activeForm,setActiveForm]=useState<"loginForm"|"registerForm">("registerForm");
  const[email,setEmail]=useState("");
  const[username,setUsername]=useState("");
  const[password,setPassword]=useState("");
  const[showPassword,setShowPassword]=useState(false);
  const[birthDate, setBirthDate] = useState("");
  const[errorBirth,setErrorBirth]=useState<string|null>(null);
  const[errorEmail,setErrorEmail]=useState<string|null>(null);
  const[errorPassword,setErrorPassword]=useState<string|null>(null);
  const[errorUsername,setErrorUsername]=useState<string|null>(null);
  const[errorMess,setErrorMess]=useState<string|null>(null);
  const[succesMess,setSuccesMess]=useState<string|null>(null)
  const[isLoading,setIsLoading]=useState(false);

    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexUsername = /^[A-Za-z0-9_-]{3,20}$/;
    const regexPassword = /^(?=.*[A-Za-z])(?=.*\d).{6,12}$/;
    

  function validate() {
    let valid = true;
    
    if(!regexEmail.test(email)){
      setErrorEmail("Podaj poprawny adres email");
      valid=false;
    } else {
      setErrorEmail(null);
    }

    if(!regexPassword.test(password)) {
      setErrorPassword("Hasło musi zawierać min 6 znaków max 12 (1 literę i cyfrę)!");
      valid = false;
    } else {
      setErrorPassword(null);
    }

    if(!regexUsername.test(username)) {
      setErrorUsername("Nazwa uzytkownika moze zawierać tylko! (cyfry,litery, _ i -)");
      valid=false;
    } else {
      setErrorUsername(null);
    }
    if(!birthDate) {
      setErrorBirth("Podaj datę urodzenia (min 18 lat)");
      valid = false;
    } else {
      setErrorBirth(null);
      const birth = new Date(birthDate);
      const today = new Date();
      let age = today.getFullYear() - birth.getFullYear();

      if(today.getMonth()<birth.getMonth() || (today.getMonth()===birth.getMonth()&&today.getDate()<birth.getDate())) {
        age--;
      }
      if(age<18) {setErrorBirth("Muisz mieć conajmiej 18lat"); valid=false;} else{setErrorBirth(null);}
      }
      return valid;
  }

  async function validateF(event:React.FormEvent) {
    event.preventDefault();
    setSuccesMess(null);
    if(!validate()) {return;}
    try 
    {

        setIsLoading(true);
          const answer = await fetch("http://localhost/glimzyskins/backend/register.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
              email,
              username,
              date_of_birth: birthDate,
              password,
            }),
            });
            const data = await answer.json();
            if(!answer.ok) {
              setErrorMess(data.message || "Wystąpił błąd podczas rejestracji");
              return;
            }
            if (!data.success) {
              setErrorMess(data.message || "Nie udało się utworzyć konta");
              return;
            }
            setErrorMess(null);
            setSuccesMess("Rejestracja zakończona pomyślnie!");

            if (data.user) {
              localStorage.setItem("user", JSON.stringify(data.user));
            }

            setEmail("");
            setBirthDate("");
            setUsername("");
            setPassword("");

            setTimeout(() => {
              router.push("/dashboardCases");
            }, 1500);
            } catch(error:unknown) {
          if (error instanceof Error) 
            {
                setErrorMess("Błąd sieci: " + error.message);
            } 
            else 
              {
                setErrorMess("Nie udało się połączyć z serwerem.");
              }
        }finally {
        setIsLoading(false);
    }
  }
    return ( 
      <div className="flex fixed inset-0 bg-black/70 justify-center z-50">

        <div className="flex justify-center items-center">

          <form onSubmit={validateF} className=" relative flex flex-col gap-1 w-105 h-152 bg-black p-8 text-gray-300 rounded-sm border border-neutral-800">
              <button type="button"onClick={ifCloseAction} className="absolute flex justify-center top-3 right-2 text-white  w-9 h-9 transition-all duration-200 hover:bg-neutral-800 border-none rounded-sm text-2xl focus:outline-none">&times;</button>

              <h1 className="text-amber-400 font-extrabold mb-2 text-3xl text-center tracking-wide">GlimzySkins</h1>

              <div className="flex justify-center gap-10 mb-3 relative border-b-2 pb-3 border-neutral-800">
               
                <button type="button" onClick={()=>{setActiveForm("loginForm"); ifSwitchAction("loginForm")}} className="font-semibold p-2 rounded-md hover:bg-neutral-900">Zaloguj się</button>
                <button type="button" onClick={()=>{setActiveForm("registerForm"); ifSwitchAction("registerForm")}} className={`relative font-semibold ${activeForm=="registerForm" && "text-amber-400"}`}>Zarejestruj się {activeForm=="registerForm"&&(<motion.div key="registerForm-underline" className="absolute left-0 bottom-0 h-0.5 bg-amber-400" initial={{width:0}} animate={{width:"100%"}}/>)}</button>
              
              </div>
              <label htmlFor="email" className="mt-[9px]">E-mail</label>
              <div className="relative">
                <input type="text"value={email} onChange={(event)=>{setEmail(event.target.value)}} placeholder="np: glimzyskins@gmail.com" className={`p-2 w-88.5 outline-none focus:ring-2 focus:ring-amber-300 bg-neutral-900 rounded-md border border-neutral-800 ${errorEmail ? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`}/>
                
                <div className="min-h-3">
                  {errorEmail&&<p className="text-xs text-red-500">{errorEmail}</p>}
                </div>
              </div>
              <label htmlFor="username">Nazwa uzytkowika</label>
            <div className="relative">
              <input type="text" value={username} onChange={(event)=>{setUsername(event.target.value)}} placeholder="np: moj_nick" className={`p-2 w-88.5 focus:ring-2 focus:ring-amber-300 outline-none bg-neutral-900 rounded-sm border border-neutral-800 ${errorUsername? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`}/>
              <div className="min-h-3">
                {errorUsername&&<p className="text-xs text-red-500">{errorUsername}</p>}
              </div>
            </div>
              <label htmlFor="password">Hasło</label>
              <div className="relative">
                <input type={showPassword?"text":"password"} value={password} onChange={(event)=>{setPassword(event.target.value)}} placeholder="np: ToHaslo123!" className={`p-2 focus:ring-2 focus:ring-amber-300 outline-none bg-neutral-900 rounded-sm border w-88.5 border-neutral-800 ${errorPassword? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`}/>
                <button type="button" className="absolute top-2.5 right-5" onClick={()=>{setShowPassword((p)=>!p)}}>{showPassword?(<EyeOff size={22} strokeWidth={1.8} />):<Eye size={22} strokeWidth={1.8} />}</button> 
                <div className="min-h-3">
                  {errorPassword&&<p className="text-xs text-red-500">{errorPassword}</p>}
                </div>
              </div>
              <label htmlFor="data_urodzenia">Data urodzenia</label>
              <div className="relative">
                <div className="min-h-3">
                  <input type="date" className={`p-2 outline-none focus:ring-2 focus:ring-amber-300 bg-neutral-900 rounded-md border w-88.5 border-neutral-800 ${errorBirth ? "placeholder-red-500 focus:ring focus:ring-red-400 border border-red-500" : ""}`} value={birthDate} onChange={(event)=>{setBirthDate(event.target.value);}}></input>
                  {errorBirth&&<p className="text-xs text-red-500">{errorBirth}</p>}
                </div>
              </div>
              {errorMess&&(<p className="text-red-500 text-sm mt-4 text-center whitespace-pre-line">{errorMess}</p>)}
              {succesMess&&(<p className="text-green-500 text-sm mt-4 text-center" >{succesMess}</p>)}

              <button type="submit" disabled={isLoading} className={`mt-6 font-semibold rounded-md py-2.5 text-white ${isLoading ? "bg-amber-600/60" : "bg-amber-500 hover:bg-amber-600"}`}>{isLoading ? "Rejestrowanie..." : "Zarejestruj się"}</button>
         
         </form>
        </div>
      </div>
    
  );
}