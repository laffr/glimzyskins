"use client";
import { useState } from "react";
import React from "react";
import { Eye, EyeOff } from "lucide-react";
import { useRouter } from "next/navigation";

export default function AdminLoginPage() {
  const router = useRouter();
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [errorUsername, setErrorUsername] = useState<string | null>(null);
  const [errorPassword, setErrorPassword] = useState<string | null>(null);
  const [errorMess, setErrorMess] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  function validate() {
    let valid = true;

    if (username.trim().length < 3) {
      setErrorUsername("Nick musi mieć minimum 3 znaki");
      valid = false;
    } else {
      setErrorUsername(null);
    }

    if (password.trim().length < 6) {
      setErrorPassword("Hasło musi posiadać co najmniej 6 znaków");
      valid = false;
    } else {
      setErrorPassword(null);
    }

    return valid;
  }

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();
    setErrorMess(null);

    if (!validate()) return;

    try {
      setIsLoading(true);

      const answer = await fetch(
        "http://localhost/glimzyskins/backend/admin/login.php",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ username, password }),
        }
      );

      const data = await answer.json();

      if (!data.success) {
        setErrorMess(data.message || "Logowanie nieudane");
        return;
      }

      localStorage.setItem("admin", JSON.stringify(data.admin));
      router.push("/admin");
    } catch (error) {
      setErrorMess("Błąd połączenia z serwerem");
    } finally {
      setIsLoading(false);
    }
  }

  return (
    <div className="flex fixed inset-0 bg-black/70 justify-center items-center z-50">
      <form
        onSubmit={handleSubmit}
        className="relative flex flex-col gap-3 w-96 bg-black p-8 text-gray-300 rounded-sm border border-neutral-800"
      >
        <h1 className="text-amber-400 font-extrabold mb-2 text-3xl text-center tracking-wide">
          Panel Admina
        </h1>

        <div className="border-b pb-3 border-neutral-800 mb-2 text-center">
          <p className="text-sm text-neutral-400">
            Zaloguj się do panelu administratora
          </p>
        </div>

        <label className="text-sm">Nazwa użytkownika</label>
        <input
          type="text"
          value={username}
          onChange={(e) => setUsername(e.target.value)}
          placeholder="admin123"
          className={`p-2 w-full bg-neutral-900 border rounded-md outline-none focus:ring-2 focus:ring-amber-300 border-neutral-800 ${
            errorUsername
              ? "border-red-500 focus:ring-red-400 placeholder-red-500"
              : ""
          }`}
        />
        {errorUsername && (
          <p className="text-xs text-red-500">{errorUsername}</p>
        )}

        <label className="text-sm">Hasło</label>
        <div className="relative">
          <input
            type={showPassword ? "text" : "password"}
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            placeholder="TwojeHaslo123"
            className={`p-2 w-full bg-neutral-900 border rounded-md outline-none focus:ring-2 focus:ring-amber-300 border-neutral-800 ${
              errorPassword
                ? "border-red-500 focus:ring-red-400 placeholder-red-500"
                : ""
            }`}
          />

          <button
            type="button"
            className="absolute top-2.5 right-3"
            onClick={() => setShowPassword((v) => !v)}
          >
            {showPassword ? <EyeOff size={22} /> : <Eye size={22} />}
          </button>
        </div>
        {errorPassword && (
          <p className="text-xs text-red-500">{errorPassword}</p>
        )}

        {errorMess && (
          <p className="text-red-500 text-sm mt-2 text-center">{errorMess}</p>
        )}

        <button
          type="submit"
          disabled={isLoading}
          className={`mt-4 font-semibold rounded-md py-2.5 text-white ${
            isLoading
              ? "bg-amber-600/60"
              : "bg-amber-500 hover:bg-amber-600"
          }`}
        >
          {isLoading ? "Loading..." : "Zaloguj się"}
        </button>
      </form>
    </div>
  );
}