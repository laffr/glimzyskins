"use client";
import LoginForm from "../components/LoginForm";
import RegisterForm from "../components/RegisterForm";
import CasesList from "./CasesList";
import { useState } from "react";
export default function WindowManager()  {

    const[currentWindow,setCurrentWindow] = useState<"registerForm"|"loginForm"|null>(null);

    const ifWindowClose = () => setCurrentWindow(null);
    const ifWindowSwitch = (goTo: "registerForm"|"loginForm") => setCurrentWindow(goTo);

    return (
        <>
        <div className="flex gap-4 text-white p-4 bg-neutral-900  justify-end shadow-[0_5px_10px_-3px_rgba(0,0,0,0.15)]">
            <button onClick={()=>setCurrentWindow("loginForm")} className=" hover:bg-neutral-800 rounded-sm p-2 h-10">Zaloguj się</button>
            <button onClick={()=>setCurrentWindow("registerForm")} className="bg-amber-600 rounded-sm hover:bg-amber-600 p-2">Zarejestruj się</button>
        </div> 
        {currentWindow === "loginForm" && (
            <LoginForm
            ifCloseAction={ifWindowClose}
            ifSwitchAction={ifWindowSwitch}
            />
        )}

        {currentWindow === "registerForm" && (
            <RegisterForm
            ifCloseAction={ifWindowClose}
            ifSwitchAction={ifWindowSwitch}
            />
        )}
       

       <div className="p-6">
               <CasesList />
             </div>
        </>
        
    );
}